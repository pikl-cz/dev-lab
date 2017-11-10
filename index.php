<?php

require __DIR__ . '/core/runner.php';
$run->just(['FileFolderTree']);

/*
 * Show root
 */
$pathDiff = '/run';
$list = new \Assist\FileFolderTree(__DIR__ . $pathDiff);
$list->setPathDiff($pathDiff);
$list->setIgnoreList(['.', '..', 'phpinfo.php', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'core', 'nbproject', '_experiments', '_extensions', 'logs', 'samples', 'tests']);
$list->run();