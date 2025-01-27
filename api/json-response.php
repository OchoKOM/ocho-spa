<?php
// Fonction pour envoyer une réponse JSON avec un code de statut
function sendJsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    
    exit();
}
