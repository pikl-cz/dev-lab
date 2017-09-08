<?php

/*
 * pages => amount of visit
 */
$pages = [
    '/produkt/ts-4000/' => 2,
    '/cs/tag/wamp/' => 3,
    '/cs/2017/03/' => 1,
    '/zitkova/rezervace-a-cenik' => 1
];

$tester = new \Assist\EnvironmentalTest();
$tester->setFileToSaveLog('multitest');
$tester->setUrl('devel', 'http://webar.pikl.cz');
$tester->setUrl('okac', 'https://www.okbase.cz');
$tester->setUrl('devel2', 'http://www.hotelkopanice.cz');
$tester->setUrl('master', 'http://gezedata.cz');
$tester->setUrl('local', 'http://devlab.dev');
$tester->setPages($pages);
$tester->setSitemap('https://www.okbase.cz/sitemap.xml', 1);
//$tester->setSitemap('mujblogsitemap.xml', 2);
$tester->find('body');
$tester->run();