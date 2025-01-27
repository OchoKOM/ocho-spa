<!-- pages/home/page.php -->
<?php
// Get all directories from this directory and check if they have a page.php or a layout.php file and store them in an array

$directories = glob(__DIR__ . '/../pages/*', GLOB_ONLYDIR);
$pages = [];
foreach ($directories as $dir) {
    $pagePath = "$dir/page.php";
    $layoutPath = "$dir/layout.php";
    if (is_file($pagePath) || is_file($layoutPath)) {
        $baseName = basename($dir);
        $pages[] = ['label' => strtoupper($baseName), 'path' => $baseName];
    }
}

?>
<main class="page-content">
    <h1>Welcome to Our SPA With Vanilla JS and PHP</h1>
    <p>Dynamic content loaded from PHP</p>
    <?php
    foreach ($pages as $page) {
    ?>
        <a href="<?=$page['path']?>"><?= $page['label']; ?></a>
    <?php
    }
    ?>
    <?= $pageContent; ?>
</main>