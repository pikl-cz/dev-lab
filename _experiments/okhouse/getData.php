<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
/*
 * Parts of core to load
 */
require __DIR__ . '/../../devLab/core/php/runner.php';
$run->just(['Visitor']);

$visitor = new \Assist\Visitor;
$page = $visitor->link('/zamestnanci-seznam/', 'http://www.oksystem.local');

//TODO - automate visitor to sublinks in a content - to get more information
//$fileEndEnd = mb_convert_encoding($page['content'], 'HTML-ENTITIES', "UTF-8");
//$fileEndEnd = utf8_encode ($page['content']);

$fileEndEnd = iconv("WINDOWS-1250", "UTF-8", $page['content']);

//dump($fileEndEnd);die;
echo $fileEndEnd;