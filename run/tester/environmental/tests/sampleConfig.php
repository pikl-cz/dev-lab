<?php

/*
 * Konfig sample
 */
$tester->setFileToSaveLog('myWeb');
$tester->setUrl('master', 'https://www.example.cz');
$tester->setSitemap('https://www.example.cz/sitemap.xml', 1);
$tester->find('sentenceInCode');
$tester->run();