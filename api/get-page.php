<?php
include "./json-response.php";
$baseDir = __DIR__ . '/../pages';
$route = trim($_GET['route'] ?? '', '/');

// Resolve route to a corresponding file
$resolvedPath = realpath("$baseDir/$route");

if ($resolvedPath && strpos($resolvedPath, realpath($baseDir)) === 0) {
    if (is_file("$resolvedPath/page.php")) {
        // Capture the output of the PHP file as HTML content
        ob_start();
        include "$resolvedPath/page.php";
        $htmlContent = ob_get_clean();
        sendJsonResponse($htmlContent);
    } elseif (is_file("$resolvedPath/layout.php")) {
        ob_start();
        include "$resolvedPath/layout.php";
        $htmlContent = ob_get_clean();
        sendJsonResponse($htmlContent);
    } else {
        // List subdirectories if no `page.php` or `layout.php` is found
        $subdirs = array_filter(glob("$resolvedPath/*"), 'is_dir');
        if (!empty($subdirs)) {
            $links = array_map(function ($dir) use ($baseDir) {
                $relativePath = trim(str_replace(realpath($baseDir), '', $dir), '/');
                $baseName = basename($relativePath);
                return "<a href=\"$relativePath\">$baseName</a>";
            }, $subdirs);
            sendJsonResponse("<h1>Available subdirectories:</h1><ul><li>" . implode("</li><li>", $links) . "</li></ul>");
        } else {
            sendJsonResponse("<h4 class=\"error\">A page directory must contain a 'page.php' or a 'layout.php' file.</h4>");
        }
    }
} else {
    sendJsonResponse("<h1>404 Page not found.</h1>");
}
