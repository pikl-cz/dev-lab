<?php

/*
 * Autoloading of classes
 */
spl_autoload_register(function ($name) {
    echo "Want to load $name.\n";
    throw new Exception("Unable to load $name.");
});

/*
 * TODO: scan all subfolders from root and include them
 * nowadays it is hotfix solution
 */

foreach ([
            'Stopwatch',
            'FileFolderTree',
            'Visitor',
         ] as $className)
{
    require __DIR__ . '/../devLab/' . $className . '.php';
}

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