<?php
require_once __DIR__ . '/spa-helpers.php';
function checkForPhpRedirect() {
    foreach (headers_list() as $header) {
        if (stripos($header, 'Location:') === 0) {
            $location = trim(substr($header, stripos($header, ':') + 1));
            sendJsonResponse(['location' => $location], 302);
            exit();
        }
    }
}

// Fonction pour envoyer une réponse JSON avec un code de statut
function sendJsonResponse($data, $statusCode = 200, $customHeaders = []) {
    while (ob_get_level() > 0) ob_end_clean();
    
    http_response_code($statusCode);
    header('Content-Type: application/json');
    
    foreach ($customHeaders as $name => $value) {
        header("$name: $value");
    }
    
    // Assurez-vous que $data est toujours un tableau
    echo json_encode($data);
    exit();
}
