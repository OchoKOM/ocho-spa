<?php
include "../api/json-response.php";

sendJsonResponse([
    'content' => '<h1>403 - Forbiden</h1><p>You are not allowed to access this resource.</p>',
    'metadata' => ['title' => "403 - Forbidden", "description" => "You are not allowed to access this resource."],
    'styles' => [],
]);
