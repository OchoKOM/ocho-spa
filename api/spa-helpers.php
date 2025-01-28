<?php

function spa_redirect(string $route, int $code = 302) {
    $cleanRoute = '/' . trim($route, '/');
    
    sendJsonResponse(
        [
            'content' => '<div class="spa-redirect">Redirection...</div>',
            'metadata' => ['title' => 'Redirection'],
            'styles' => []
        ], 
        $code,
        ['X-SPA-Redirect' => $cleanRoute]
    );
    exit;
}
