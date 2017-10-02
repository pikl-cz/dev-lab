<?php

require __DIR__ . '/devLab/bootstrap.php';

try {
	$pass = new \Assist\Password();
    $hash = $pass->getHash('honza');
	$pass->verify('honza', $hash);
	
	$list = new \Assist\FileFolderTree(__DIR__);
    $list->setIgnoreList(['.', '..', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'devLab', 'nbproject']);
    $list->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}