<?php

require __DIR__ . '/devLab/bootstrap.php';

try {
    $list = new \Assist\FileFolderTree(__DIR__);
    $list->setIgnoreList(['.', '..', 'index.php', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'devLab', 'nbproject']);
=======
	$pass = new \Assist\Password();
    $hash = $pass->getHash('honza');
	$pass->verify('honza', $hash);

	$list = new \Assist\FileFolderTree(__DIR__);
    $list->setIgnoreList(['.', '..', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'node_modules', 'angularjs', 'reactjs', 'devLab', 'nbproject']);
>>>>>>> origin/master
    $list->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}