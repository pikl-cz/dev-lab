<?php

/*
* Nastavení bufferu
*/
ob_implicit_flush(true);
ob_end_flush();

/*
 * Formatted var_dump
 */
echo "<pre>";
ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

/*
 * Easier call of var_dump
 */
function dump($expression)
{
    return var_dump($expression);
}

/*
 * Autoloading of classes
 */
spl_autoload_register(function ($name) {
    echo "Want to load $name.\n";
    throw new Exception("Unable to load $name.");
});

/*
 * TODO: automatically scan also subfolders
 */
require __DIR__ . '/FileFolderTree.php';
$list = new \Assist\FileFolderTree(__DIR__, true);
$list->setIgnoreList(['.', '..', 'templates', 'bootstrap.php', 'FileFolderTree.php']);
foreach ($list->buildTree() as $className)
{
    require __DIR__ . '/' . $className;
}