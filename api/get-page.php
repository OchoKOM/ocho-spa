<?php
include "./json-response.php";
$baseDir = realpath(__DIR__ . '/../pages');
$route = trim($_GET['route'] ?? '', '/');

if (!$baseDir) {
    sendJsonResponse([
        'content' => '<h1>Server error: pages directory not found</h1>',
        'metadata' => ['title' => '', 'description' => ''],
        'styles' => []
    ]);
    exit;
}

$resolvedPath = realpath("$baseDir/$route");

if ($resolvedPath && strpos($resolvedPath, $baseDir) === 0) {
    // Collect metadata and styles
    $directories = [];
    $currentDir = $resolvedPath;
    while (true) {
        $directories[] = $currentDir;
        if ($currentDir === $baseDir) break;
        $currentDir = dirname($currentDir);
        if (strpos($currentDir, $baseDir) !== 0) break;
    }

    // Find closest metadata
    $metadata = ['title' => '', 'description' => ''];
    foreach ($directories as $dir) {
        $metadataPath = $dir . '/metadata.json';
        if (file_exists($metadataPath)) {
            $data = json_decode(file_get_contents($metadataPath), true);
            if ($data) {
                if (isset($data['title'])) $metadata['title'] = $data['title'];
                if (isset($data['description'])) $metadata['description'] = $data['description'];
                break;
            }
        }
    }

    // Collect all styles
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

    if (is_file("$resolvedPath/page.php")) {
        ob_start();
        include "$resolvedPath/page.php";
        $pageContent = ob_get_clean();

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
            $htmlContent = $pageContent;
        }

        sendJsonResponse([
            'content' => $htmlContent,
            'metadata' => $metadata,
            'styles' => $styles
        ]);
    } elseif (is_file("$resolvedPath/layout.php")) {
        ob_start();
        include "$resolvedPath/layout.php";
        $htmlContent = ob_get_clean();
        sendJsonResponse([
            'content' => $htmlContent,
            'metadata' => $metadata,
            'styles' => $styles
        ]);
    } else {
        $subdirs = array_filter(glob("$resolvedPath/*"), 'is_dir');
        if (!empty($subdirs)) {
            $links = array_map(function ($dir) use ($baseDir) {
                $relativePath = trim(str_replace($baseDir, '', $dir), '/');
                $relativePath = str_replace('\\', '/', $relativePath);
                $baseName = basename($relativePath);
                return '<a href="'.$relativePath.'">'.$baseName.'</a>';
            }, $subdirs);
            $content = "<h1>Available subdirectories:</h1><ul><li>" . implode("</li><li>", $links) . "</li></ul>";
            sendJsonResponse([
                'content' => $content,
                'metadata' => $metadata,
                'styles' => $styles
            ]);
        } else {
            sendJsonResponse([
                'content' => '<h4 class="error">A page directory must contain a "page.php" or "layout.php" file.</h4>',
                'metadata' => $metadata,
                'styles' => $styles
            ]);
        }
    }
} else {
    sendJsonResponse([
        'content' => '<h1>404 Page not found.</h1>',
        'metadata' => ['title' => '', 'description' => ''],
        'styles' => []
    ]);
}
