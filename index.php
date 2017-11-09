<?php

require __DIR__ . '/core/bootstrap.php';

/*
 * Výpis základního stromu
 */
try {
    $list = new \Assist\FileFolderTree(__DIR__);
    $list->setIgnoreList(['.', '..', 'phpinfo.php', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'core', 'nbproject', '_experiments', '_extensions', 'logs', 'samples', 'tests']);
    $list->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}