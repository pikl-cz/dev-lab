<?php

/*
 * Konfigurace testování pro okbase na masteru
 */
$tester = new \Assist\EnvironmentalTest();
$tester->setFileToSaveLog('okbase');
$tester->setUrl('master', 'https://www.okbase.cz');
$tester->setSitemap('https://www.okbase.cz/sitemap.xml', 1);
$tester->find('oksystem');
$tester->run();