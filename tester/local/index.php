<?php
/*
 * TODO
 * - selectbox - choose rootDir where start tree of scripts
 *       - if not selected - try to find $rootTestFolderName if not exists lets create;
 * - get errors - if some script crashed
 *      - write it next to result of speed
 *      - the row will be red colored
 * - design
 *      - table
 *      - showAble divs with log
 * - tree structure
 * - timenodes in script (measure part of scripts)
 * - check files to compare
 */

require __DIR__ . '/../../devLab/bootstrap.php';

$stopwatch = new \Assist\Stopwatch();

$rootTestFolderName = 'SpeedSamples';
$originAppRootDir = $appRootDir = __DIR__ . '/' . $rootTestFolderName; //root location for *.php test files

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
    $execTime = $stopwatch->getExecutionTime($executionTimeStart, $executionTimeEnd);
		$message = '<hr><b>Total Execution Time:</b> ' . $execTime->min . ' ' . $execTime->sec . ' ' . $execTime->ms;
	} else {
		$message = 'Ne? Jak je libo.';		
	}	
	echo '<p><strong>' . $message . '</strong></p>';		
}



/*
 *  Scan folder with *.php files to run 
 */
function listFolders($appRootDir) {
    $stopwatch = new \Assist\Stopwatch();

	global $originAppRootDir;
	global $rootTestFolderName;

	$bugTypes = ['Warning', 'Notice', 'Fatal Error'];

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
				$log = [];
                unset($output);
				exec('php -f ' . $appRootDir . '/' . $item, $output, $return);
				foreach($output as $row)
                {
                    foreach ($bugTypes as $bug)
                    {
                        if (strpos($row, $bug) !== false)
                        {
                            $log[$bug][] = $row;
                        }
                    }
                }
				$executionTimeEnd = microtime(true);
                $execTime = $stopwatch->getExecutionTime($executionTimeStart, $executionTimeEnd);
				echo '<li>' . $appRootDir . '/<strong><a href="'. $rootTestFolderName . '/' . str_replace($originAppRootDir, '', $appRootDir) . '/' . $item . '">' . $item . '</a></strong> [' . $execTime->min . 'min ' . $execTime->sec . 's ' . $execTime->ms . 'ms ]';
                if (!empty($log))
                {
                    foreach ($bugTypes as $bug)
                    {
                        if (array_key_exists($bug, $log) && count($log[$bug]) > 0)
                        {
                            echo ' [' . $bug . ': ' . count($log[$bug]) .'] ';
                        }
                    }

                }
				echo '</li>';
                //var_dump($log);
			}
		}
    }
	echo '</ul>';
}