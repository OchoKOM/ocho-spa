<?php
include "./json-response.php";

$baseDir = realpath(__DIR__ . '/../pages');
$route = trim($_GET['route'] ?? '', '/');

if (!$baseDir) {
    sendJsonResponse([
        'content'    => '<h1>500 - Internal Server Error : </h1><p>Pages directory not found</p>',
        'metadata'   => ['title' => '500 - Internal Server Error', 'description' => ''],
        'styles'     => []
    ]);
    exit;
}

$resolvedPath = realpath("$baseDir/$route");
$pageContent = "";
if ($resolvedPath && strpos($resolvedPath, $baseDir) === 0) {
    // --- Récupération des metadata et styles ---
    $directories = [];
    $currentDir = $resolvedPath;
    while (true) {
        $directories[] = $currentDir;
        if ($currentDir === $baseDir) break;
        $currentDir = dirname($currentDir);
        if (strpos($currentDir, $baseDir) !== 0) break;
    }

    // Recherche de metadata la plus proche
    $metadata = ['title' => '', 'description' => ''];
    foreach ($directories as $dir) {
        $metadataPath = $dir . '/metadata.json';
        if (file_exists($metadataPath)) {
            $data = json_decode(file_get_contents($metadataPath), true);
            if ($data) {
                if (isset($data['title']))       $metadata['title'] = $data['title'];
                if (isset($data['description'])) $metadata['description'] = $data['description'];
                break;
            }
        }
    }

    // Récupération de tous les styles
    $styles = [];
    foreach (array_reverse($directories) as $dir) {
        $metadataPath = $dir . '/metadata.json';
        if (file_exists($metadataPath)) {
            $data = json_decode(file_get_contents($metadataPath), true);
            if ($data && isset($data['styles']) && is_array($data['styles'])) {
                $styles = array_merge($styles, $data['styles']);
            }
        }
    }

    /**
     * Recherche récursive d'un layout parent non marqué "as page"
     * @param string $startPath
     * @param string $baseDir
     * @return string|null Chemin du layout parent ou null s'il n'existe pas
     */
    function findParentLayout($startPath, $baseDir) {
        $currentPath = dirname($startPath);
        while (strpos($currentPath, $baseDir) === 0) {
            $possibleLayout = $currentPath . '/layout.php';
            if (is_file($possibleLayout)) {
                $content = file_get_contents($possibleLayout);
                // Si le layout ne commence pas par le marqueur "as page", on le considère comme parent
                if (!preg_match('/^\s*<\?php\s*("as page")/', $content)) {
                    return $possibleLayout;
                }
            }
            if ($currentPath === $baseDir) break;
            $currentPath = dirname($currentPath);
        }
        return null;
    }

    // --- Cas où le répertoire contient un fichier page.php ---
    if (is_file("$resolvedPath/page.php")) {
        // Récupérer le contenu de page.php dans $pageContent
        ob_start();
        include "$resolvedPath/page.php";
        $pageContent = ob_get_clean();

        // Vérifier si un layout.php est présent dans le même dossier
        if (is_file("$resolvedPath/layout.php")) {
            $layoutPath = "$resolvedPath/layout.php";

            // --- Vérification automatique dans le fichier layout ---
            $layoutFileContent = file_get_contents($layoutPath);
            // On vérifie que le layout fait référence à $pageContent
            $tokens = token_get_all($layoutFileContent);
            $found = false;
            foreach ($tokens as $token) {
                // Si c'est un token (tableau) et non un simple caractère
                if (is_array($token)) {
                    // On ignore les commentaires et la documentation
                    if ($token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
                        continue;
                    }
                    // Vérifier si le token est une variable et s'il s'agit de "$pageContent"
                    if ($token[0] === T_VARIABLE && $token[1] === '$pageContent') {
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                sendJsonResponse([
                    'content'  => '<p class="error">Error: the layout file (<code>layout.php</code>) must use a variable named <code>$pageContent</code> to display the content of <code>page.php</code>.</p>',
                    'metadata' => $metadata,
                    'styles'   => $styles
                ]);
                exit;
            }

            // --- Gestion des layouts enfants ---
            // Si le layout commence par le marqueur "as page", il est considéré comme enfant
            if (preg_match('/^\s*<\?php\s*("as page")/', $layoutFileContent)) {
                $parentLayoutPath = findParentLayout($resolvedPath, $baseDir);
                // Rendu du layout enfant pour obtenir le contenu à injecter dans $pageContent
                ob_start();
                include $layoutPath;
                $childContent = ob_get_clean();
                
                if ($parentLayoutPath) {
                    // Injection du contenu enfant dans $pageContent et rendu du layout parent
                    ob_start();
                    $pageContent = $childContent; // On définit $pageContent pour le layout parent
                    include $parentLayoutPath;
                    $htmlContent = ob_get_clean();
                } else {
                    // Aucun layout parent trouvé : on utilise le layout enfant seul
                    $htmlContent = $childContent;
                }
            } else {
                // Layout classique
                ob_start();
                include $layoutPath;
                $htmlContent = ob_get_clean();
            }
        } else {
            // --- Sinon, recherche d'un layout dans les dossiers parents ---
            $currentPath = $resolvedPath;
            $layoutPath = null;
            while (true) {
                $possibleLayout = $currentPath . '/layout.php';
                if (is_file($possibleLayout)) {
                    $layoutPath = $possibleLayout;
                    break;
                }
                if ($currentPath === $baseDir) break;
                $currentPath = dirname($currentPath);
            }
            if ($layoutPath) {
                ob_start();
                include $layoutPath;
                $htmlContent = ob_get_clean();
            } else {
                // Aucun layout trouvé : on affiche simplement $pageContent.
                $htmlContent = $pageContent;
            }
        }

        sendJsonResponse([
            'content'  => $htmlContent,
            'metadata' => $metadata,
            'styles'   => $styles
        ]);
    }
    // --- Cas où le répertoire contient uniquement un fichier layout.php ---
    elseif (is_file("$resolvedPath/layout.php")) {
        $layoutPath = "$resolvedPath/layout.php";
        $layoutFileContent = file_get_contents($layoutPath);
        // Si le layout est marqué "as page", on cherche un layout parent
        if (preg_match('/^\s*<\?php\s*("as page")/', $layoutFileContent)) {
            $parentLayoutPath = findParentLayout($resolvedPath, $baseDir);
            // Rendu du layout enfant pour récupérer le contenu destiné à $pageContent
            ob_start();
            include $layoutPath;
            $childContent = ob_get_clean();
            
            if ($parentLayoutPath) {
                // Injection du contenu enfant dans $pageContent et rendu du layout parent
                ob_start();
                $pageContent = $childContent;
                include $parentLayoutPath;
                $htmlContent = ob_get_clean();
            } else {
                // Aucun parent trouvé : utilisation du layout enfant seul
                $htmlContent = $childContent;
            }
        } else {
            // Layout classique
            ob_start();
            include $layoutPath;
            $htmlContent = ob_get_clean();
        }
        sendJsonResponse([
            'content'  => $htmlContent,
            'metadata' => $metadata,
            'styles'   => $styles
        ]);
    }
    // --- Sinon, s'il y a des sous-dossiers, afficher une liste de liens ---
    else {
        $subdirs = array_filter(glob("$resolvedPath/*"), 'is_dir');
        if (!empty($subdirs)) {
            $links = array_map(function ($dir) use ($baseDir) {
                $relativePath = trim(str_replace($baseDir, '', $dir), '/');
                $relativePath = str_replace('\\', '/', $relativePath);
                $baseName = basename($relativePath);
                return '<a href="' . $relativePath . '">' . $baseName . '</a>';
            }, $subdirs);
            $content = "<h1>Available subdirectories:</h1><ul><li>" . implode("</li><li>", $links) . "</li></ul>";
            sendJsonResponse([
                'content'  => $content,
                'metadata' => $metadata,
                'styles'   => $styles
            ]);
        } else {
            sendJsonResponse([
                'content'  => '<p class="error">A page directory must contain a <code>page.php</code> or <code>layout.php</code> file.</p>',
                'metadata' => $metadata,
                'styles'   => $styles
            ]);
        }
    }
    exit;
}
sendJsonResponse([
    'content'    => '<h1>404 - Page not found.</h1><p>The requested ressource was not found on this server.</p>',
    'metadata'   => ['title' => '404 - Page not found', 'description' => ''],
    'styles'     => []
]);
