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

// Fonction pour récupérer tous les fichiers JS dans un répertoire et ses sous-répertoires
function getAllJsFiles($directory) {
    $jsFiles = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'js') {
            $jsFiles[] = $file->getPathname();
        }
    }

    return $jsFiles;
}

// Fonction pour vérifier les fichiers JS
function checkJsFilesForChanges() {
    $jsDirectory = __DIR__ . '/../app/js';
    $jsFiles = getAllJsFiles($jsDirectory);

    $lastModifiedTimes = [];

    foreach ($jsFiles as $file) {
        if (file_exists($file)) {
            $lastModifiedTimes[$file] = filemtime($file);
        }
    }

    // Stocker les derniers temps de modification dans une session ou un fichier
    session_start();
    if (isset($_SESSION['js_last_modified'])) {
        foreach ($_SESSION['js_last_modified'] as $file => $time) {
            if (isset($lastModifiedTimes[$file]) && $lastModifiedTimes[$file] > $time) {
                // Un fichier JS a été modifié
                $_SESSION['js_last_modified'] = $lastModifiedTimes;
                return true;
            }
        }
    } else {
        $_SESSION['js_last_modified'] = $lastModifiedTimes;
    }

    return false;
}
// Fonction pour vérifier les fichiers JS
function checkCSSFilesForChanges() {
    $jsDirectory = __DIR__ . '/../app/css';
    $jsFiles = getAllJsFiles($jsDirectory);

    $lastModifiedTimes = [];

    foreach ($jsFiles as $file) {
        if (file_exists($file)) {
            $lastModifiedTimes[$file] = filemtime($file);
        }
    }

    // Stocker les derniers temps de modification dans une session ou un fichier
    session_start();
    if (isset($_SESSION['css_last_modified'])) {
        foreach ($_SESSION['css_last_modified'] as $file => $time) {
            if (isset($lastModifiedTimes[$file]) && $lastModifiedTimes[$file] > $time) {
                // Un fichier JS a été modifié
                $_SESSION['css_last_modified'] = $lastModifiedTimes;
                return true;
            }
        }
    } else {
        $_SESSION['css_last_modified'] = $lastModifiedTimes;
    }

    return false;
}

// Fonction pour envoyer une réponse JSON avec un code de statut
function sendJsonResponse($data, $statusCode = 200, $customHeaders = []) {
    while (ob_get_level() > 0) ob_end_clean();

    // Vérifier si les fichiers JS ont changé
    $canRefresh = checkJsFilesForChanges();
    
    // Générer un ETag basé sur le contenu de la réponse
    $etag = md5(json_encode($data));

    // En-têtes de cache
    header('Content-Type: application/json');
    header("x-spa-refresh: $canRefresh");
    header("Cache-Control: max-age=60, public"); // Cache pendant 60 secondes
    header("ETag: $etag");

    // Vérifier si le client a déjà la version mise en cache
    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
        // Le client a déjà la version la plus récente
        http_response_code(304); // Not Modified
        exit();
    }

    // Ajouter des en-têtes personnalisés
    foreach ($customHeaders as $name => $value) {
        header("$name: $value");
    }
    
    // Envoyer la réponse JSON
    echo json_encode($data);
    exit();
}
