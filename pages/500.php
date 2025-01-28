<?php
    include "../api/json-response.php";

    sendJsonResponse([
        'content' => '<h1>500 - Internal Server Error</h1><p>An error occurred on the server. Please try again later.</p>',
        'metadata' => ['title' => 'Internal Server Error', 'description' => 'An error occurred on the server. Please try again later.'],
        'styles' => [],
    ]);
?>