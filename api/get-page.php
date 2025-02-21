<?php
include "./json-response.php";

$baseDir = realpath(__DIR__ . '/../pages');
$route = trim($_GET['route'] ?? '', '/');

if (!$baseDir) {
    sendJsonResponse([
        'content'    => '<h1>500 - Internal Server Error</h1><p>Pages directory not found</p>',
        'metadata'   => ['title' => '500 - Internal Server Error', 'description' => ''],
        'styles'     => []
    ]);
    exit;
}

$resolvedPath = realpath("$baseDir/$route");
if (!$resolvedPath || strpos($resolvedPath, $baseDir) !== 0) {
    sendJsonResponse([
        'content'    => '<h1>404 - Page not found.</h1><p>The requested resource was not found on this server.</p>',
        'metadata'   => ['title' => '404 - Page not found', 'description' => ''],
        'styles'     => []
    ]);
    exit;
}

/**
 * Récupère tous les répertoires entre $resolvedPath et $baseDir.
 */
function getDirectories($resolvedPath, $baseDir) {
    $directories = [];
    $currentDir = $resolvedPath;
    while (true) {
        $directories[] = $currentDir;
        if ($currentDir === $baseDir) break;
        $currentDir = dirname($currentDir);
        if (strpos($currentDir, $baseDir) !== 0) break;
    }
    return $directories;
}

/**
 * Recherche les métadonnées dans les répertoires (du plus proche au plus éloigné).
 */
function getMetadata($directories) {
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
    return $metadata;
}

/**
 * Récupère tous les styles définis dans les fichiers metadata.json.
 */
function getStyles($directories) {
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
    return $styles;
}

/**
 * Recherche récursive d'un layout parent non marqué "as page".
 */
function findParentLayout($startPath, $baseDir) {
    $currentPath = dirname($startPath);
    while (strpos($currentPath, $baseDir) === 0) {
        $possibleLayout = $currentPath . '/layout.php';
        if (is_file($possibleLayout)) {
            $content = file_get_contents($possibleLayout);
            if (!preg_match('/^\s*<\?php\s*("as page")/', $content)) {
                return $possibleLayout;
            }
        }
        if ($currentPath === $baseDir) break;
        $currentPath = dirname($currentPath);
    }
    return null;
}

/**
 * Vérifie que le fichier layout utilise la variable $pageContent.
 */
function checkLayoutForPageContent($layoutPath) {
    $layoutContent = file_get_contents($layoutPath);
    $tokens = token_get_all($layoutContent);
    foreach ($tokens as $token) {
        if (is_array($token)) {
            if ($token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) continue;
            if ($token[0] === T_VARIABLE && $token[1] === '$pageContent') {
                return true;
            }
        }
    }
    return false;
}

/**
 * Gère l'inclusion d'un layout.
 * Si le layout est marqué "as page", recherche un layout parent pour envelopper le contenu.
 */
function renderWithLayout($layoutPath, $baseDir, $resolvedPath, $pageContent = null) {
    $layoutContent = file_get_contents($layoutPath);
    // Si un contenu de page est fourni, vérifier que le layout l'utilise
    if ($pageContent !== null && !checkLayoutForPageContent($layoutPath)) {
        sendJsonResponse([
            'content'  => '<p class="error">Error: the layout file (<code>layout.php</code>) must use a variable named <code>$pageContent</code> to display the content of <code>page.php</code>.</p>'
        ]);
        exit;
    }
    if (preg_match('/^\s*<\?php\s*("as page")/', $layoutContent)) {
        $parentLayoutPath = findParentLayout($resolvedPath, $baseDir);
        ob_start();
        include $layoutPath;
        $childContent = ob_get_clean();
        if ($parentLayoutPath) {
            ob_start();
            $pageContent = $childContent;
            include $parentLayoutPath;
            return ob_get_clean();
        } else {
            return $childContent;
        }
    } else {
        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }
}

$directories = getDirectories($resolvedPath, $baseDir);
$metadata    = getMetadata($directories);
$styles      = getStyles($directories);

$htmlContent = "";

// Cas où le répertoire contient un fichier page.php
if (is_file("$resolvedPath/page.php")) {
    ob_start();
    include "$resolvedPath/page.php";
    $pageContent = ob_get_clean();

    // Si un layout.php existe dans le même répertoire, l'utiliser
    if (is_file("$resolvedPath/layout.php")) {
        $htmlContent = renderWithLayout("$resolvedPath/layout.php", $baseDir, $resolvedPath, $pageContent);
    } else {
        // Recherche d'un layout dans les répertoires parents
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
        $htmlContent = $layoutPath
            ? renderWithLayout($layoutPath, $baseDir, $resolvedPath, $pageContent)
            : $pageContent;
    }
}
// Cas où le répertoire contient uniquement un fichier layout.php
elseif (is_file("$resolvedPath/layout.php")) {
    $htmlContent = renderWithLayout("$resolvedPath/layout.php", $baseDir, $resolvedPath);
}
// Sinon, s'il y a des sous-dossiers, affiche une liste de liens
else {
    $subdirs = array_filter(glob("$resolvedPath/*"), 'is_dir');
    if (!empty($subdirs)) {
        $links = array_map(function ($dir) use ($baseDir) {
            $relativePath = trim(str_replace($baseDir, '', $dir), '/');
            $relativePath = str_replace('\\', '/', $relativePath);
            return '<a href="' . $relativePath . '">' . basename($relativePath) . '</a>';
        }, $subdirs);
        $htmlContent = "<h1>Available subdirectories:</h1><ul><li>" . implode("</li><li>", $links) . "</li></ul>";
    } else {
        $htmlContent = '<p class="error">A page directory must contain a <code>page.php</code> or <code>layout.php</code> file.</p>';
    }
}

sendJsonResponse([
    'content'  => $htmlContent,
    'metadata' => $metadata,
    'styles'   => $styles
]);
