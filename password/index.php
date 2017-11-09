<h2>Tester</h2>
<ul>
    <li><a href="environmental/">Environmentální testy</a></li>
    <li><a href="local/">Lokální soubory</a></li>
</ul>

<?php

require __DIR__ . '/devLab/bootstrap.php';

/*
 * Pokusy pro generování a prolamování silného hesla
 */
try {
    $pass = new \Assist\Password();
    $hash = $pass->getHash('honza');
	$pass->verify('honza', $hash);
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

	