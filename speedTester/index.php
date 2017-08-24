<?php
$rootTestFolderName = 'SpeedSamples';
$originAppRootDir = $appRootDir = __DIR__ . '/' . $rootTestFolderName; //root location for *.php test files

/*
 * var_dump format and setup
 */
echo "<pre>";
ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

/*
 * Run speed test
 */
echo '<p>Přeješ si test rychlosti skriptů ve složce <strong>' . $appRootDir . '</strong>? <a href="?submit=true">Ano</a> / <a href="?submit=false">Ne</a></p>';

if (isset($_REQUEST['submit'])) {
  $submit = $_REQUEST['submit'];
	if ($submit === 'true') {
    $executionTimeStart = microtime(true); // timer-start

    //usleep(2900000); //example of time: 2s 9ms
	echo '<hr>';
    listFolders($appRootDir);

    $executionTimeEnd = microtime(true); // timer-end
		$message = '<hr><b>Total Execution Time:</b> ' . getExecutionTime($executionTimeStart, $executionTimeEnd) . '';		
	} else {
		$message = 'Ne? Jak je libo.';		
	}	
	echo '<p><strong>' . $message . '</strong></p>';		
}

/*
 * Prepare total execution time X-min Y-s Z-ms
 */
function getExecutionTime($executionTimeStart, $executionTimeEnd) {
	$executionTimeResult = - ($executionTimeStart - $executionTimeEnd);

	$milliseconds = round($executionTimeResult * 1000);
	$total = explode(".", $milliseconds / 1000);
  if (array_key_exists(1, $total)) {
    $ms = $total[1];
  } else {
    $ms = 0;
  }
	
	if ($total[0] > 60) {
		$min = $total[0] / 60;
		$sec = $total[0] / 60 - $min;
	} else {
		$min = 0;
		$sec = $total[0];
	}
	return '<b>' . $min . '</b>min <b>' . $sec . '</b>s <b>' . $ms . '</b>ms';
}

/*
 *  Scan folder with *.php files to run 
 */
function listFolders($appRootDir) {
	global $originAppRootDir;
	global $rootTestFolderName;
	
    $folder = scandir($appRootDir);
		
    unset($folder[array_search('.', $folder, true)]);
    unset($folder[array_search('..', $folder, true)]);
	
    if (count($folder) < 1) {
        return;
	}
		
	echo '<ul>';
    foreach($folder as $item){    				
		$itemPath = $appRootDir . '/' . $item;		
		
        if (is_dir($itemPath)) {
			//Recursive
			listFolders($itemPath);
		} else {
			$path_parts = pathinfo($itemPath);		
			if ($path_parts['extension'] === 'php' and file_exists($itemPath)) {
				$executionTimeStart = microtime(true);				
				exec('php -f ' . $appRootDir . '/' . $item, $output, $return); 
				$executionTimeEnd = microtime(true);
				echo '<li>' . $appRootDir . '/<strong><a href="'. $rootTestFolderName . '/' . str_replace($originAppRootDir, '', $appRootDir) . '/' . $item . '">' . $item . '</a></strong> [' . getExecutionTime($executionTimeStart, $executionTimeEnd) . ']</li>';				
			}			
		}
    }
	echo '</ul>';
}