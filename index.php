<?php
// Serve the main SPA layout
$root = ""
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPA with History API</title>
    <link rel="stylesheet" href="/app/css/global.css">
    <link rel="shortcut icon" href="/favicon.svg" type="image/x-icon">
    <script defer src="<?=$root ?>/app/js/app" type="module"></script>
</head>
<body>
    <div id="app">Loading...</div>
</body>
</html>
