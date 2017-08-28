<?php

require __DIR__ . '/devLab/bootstrap.php';
require __DIR__ . '/devLab/FileFolderTree.php';

try {
    $list = new \Assist\FileFolderTree(__DIR__);
    $list->setIgnoreList(['.', '..', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'devLab']);
    $list->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}