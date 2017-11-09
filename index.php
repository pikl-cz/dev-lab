<?php

require __DIR__ . '/devLab/bootstrap.php';

/*
 * Výpis základního stromu
 */
try {
    $list = new \Assist\FileFolderTree(__DIR__);
    $list->setIgnoreList(['.', '..', 'phpinfo.php', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'devLab', 'nbproject', '_experiments', '_extensions']);
    $list->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}