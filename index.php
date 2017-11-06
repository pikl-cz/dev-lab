<?php

require __DIR__ . '/devLab/bootstrap.php';

try {
    $list = new \Assist\FileFolderTree(__DIR__);
    $list->setIgnoreList(['.', '..', 'index.php', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'devLab', 'nbproject']);
    $list->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}