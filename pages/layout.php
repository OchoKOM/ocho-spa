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
    <h1>Bienvenue sur notre Application Monopage (SPA) avec JavaScript et PHP</h1>
    <nav>
        <ul>
            <?php
            foreach ($pages as $page) {
                ?>
                <li><a href="<?= $page['path'] ?>"><?= $page['label']; ?></a></li>
                <?php
            }
            ?>
        </ul>
    </nav>
    <h2>Contenu des pages</h2>
    <?= $pageContent; ?>
</main>