<?php

/*
 * Parts of core to load
 */
require __DIR__ . '/../../core/runner.php';
$run->just(['Visitor']);

$visitor = new \Assist\Visitor;
$page = $visitor->link('/zamestnanci-seznam/', 'http://www.oksystem.local');

//TODO - automate visitor to sublinks in a content - to get more information

dump($page['content']);