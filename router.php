<?php
// router.php

include($_SERVER['DOCUMENT_ROOT'] . '/api/json-response.php');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestPath = $_SERVER['DOCUMENT_ROOT'] . $requestUri;

// Démarrage du buffer de sortie
ob_start();

// 2. Vérification des fichiers existants
if (file_exists($requestPath) && (is_file($requestPath) || is_dir($requestPath))) {
    ob_end_clean();
    return false;
}

// 3. Gestion des extensions
$phpFile = $requestPath . '.php';
$jsFile = $requestPath . '.js';

if (file_exists($phpFile)) {
    $_SERVER['SCRIPT_NAME'] = $requestUri;
    $_SERVER['SCRIPT_FILENAME'] = $phpFile;
    include($phpFile);
} elseif (file_exists($jsFile)) {
    header('Content-Type: application/javascript');
    readfile($jsFile);
} else {
    include($_SERVER['DOCUMENT_ROOT'] . '/index.php');
}

// Récupération du contenu et du code HTTP
$content = ob_get_clean();
$httpCode = http_response_code();

// Gestion des erreurs personnalisées
switch ($httpCode) {
    case 403:
        header_remove();
        sendJsonResponse([
            'content' => '<h1>403 - Forbiden</h1><p>You are not allowed to access this resource.</p>',
            'metadata' => ['title' => "403 - Forbidden", "description" => "You are not allowed to access this resource."],
            'styles' => [],
        ]);
        break;
        
    case 500:
        header_remove();
        sendJsonResponse([
            'content' => '<h1>500 - Server Error</h1><p>An internal server error occurred.</p>',
            'metadata' => ['title' => "500 - Server Error", "description" => "An internal server error occurred."],
            'styles' => [],
        ]);
        break;

    default:
        echo $content;
        break;
}

exit;

