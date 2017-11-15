<?php

require __DIR__ . '/devLab/core/php/runner.php';
$run->just(['FileFolderTree']);

/*
 * Show root
 */
$pathDiff = '/devLab/tools';
$list = new \Assist\FileFolderTree(__DIR__ . $pathDiff);
$list->setPathDiff($pathDiff);
$list->setIgnoreList(['.', '..', 'phpinfo.php', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'core', 'nbproject', '_experiments', '_extensions', 'logs', 'samples', 'tests']);
$list->run();