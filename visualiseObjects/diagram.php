<?php

require __DIR__ . '/../devLab/bootstrap.php';

/*
 * TODO: načtení stromu složek a php souborů a jejich obsahů
 * TODO: u každého souboru zmapování classy, fcí atd...
 * TODO: vykreslení
 *
 */

try {
    $list = new \Assist\FileFolderTree(__DIR__ . '/../phpExamples/');
    $list->setIgnoreList(['.', '..', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'devLab', 'nbproject']);
    $list->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

