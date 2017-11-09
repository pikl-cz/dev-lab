<h2>Password</h2>

<?php

require __DIR__ . '/../../core/bootstrap.php';

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

	