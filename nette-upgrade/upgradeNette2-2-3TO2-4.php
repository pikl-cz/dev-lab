<?php
echo "<pre>";
ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

/*
 * Výjimky: názvu modelů
 */
function getConversionException() {
	return array(
		//'old' => 'new'
	);
}

/*
 * Konverze: $this->context->...->... na: $this->getService('')->...
 */
function getServiceConversion($fileContent) {	
	$needle = '$this->context->';
	$lastPos = 0;
	$pattern = '/\$this->context->(\w*)->/';
	$replacement = '$this->context->getService("${1}")->';			

	if (preg_match_all($pattern, $fileContent, $matches)) {		
		echo '<table>';
		foreach($matches[1] as $match) {
			echo '<tr>';
			$exceptions = getConversionException();
			if (array_key_exists($match, $exceptions)) {
				$serviceName = $exceptions[$match];
			} else {
				$serviceName = $match;
			}			
			//$pattern = '/\\' . $needle . $match . '->/';	
			$pattern = $needle . $match . '->';	
			$replacement = $needle . 'getService(\'' . $serviceName . '\')->';
			echo '<td style="color:red">' . $pattern . '</td><td style="color:green">' . $replacement . '</td>';
			//echo $replacement;
			//preg_replace($pattern, $replacement, $fileContent);
			$fileContent = str_replace($pattern, $replacement, $fileContent);
			echo '</tr>';
		}
		echo '</table>';
	}		

	return $fileContent;
}

/*
 * Projde strom složek a pracuje pouze s PHP soubory
 */
function listFolders($appRootDir){
	//Obsah složky
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
			//Pokud je složka - rekurzivně se zavolá funkce
			listFolders($itemPath);
		} else {
			$path_parts = pathinfo($itemPath);		
			if ($path_parts['extension'] === 'php' and file_exists($itemPath)) {
				//Pokud je PHP soubor								
				echo '<li>' . $appRootDir . '/<strong>' . $item . '</strong>';	
				
				$fileContent = file_get_contents($itemPath);
				$convertedFileContent = getServiceConversion($fileContent);												
				
				//Uložení úprav v souboru
				file_put_contents($itemPath,$convertedFileContent);				
				//var_dump($convertedFileContent);
				echo '</li>';	
			}			
		}
    }
	echo '</ul>';
}

/*
 * Spuštění
 */
echo '<p><em>Poznámka před spuštěním: změny si zkontroluj v GITu (bez něj operaci nelze vrátit zpět).</em></p>';
echo '<p>Přeješ si spustit úpravy v .php souborech (upgrade NETTE 2.2.3 -> 2.4)? <a href="?submit=true">Ano</a> / <a href="?submit=false">Ne</a></p>';

if (isset($_REQUEST['submit'])) {
	$submit = $_REQUEST['submit'];
	if ($submit === 'true') {
		$appRootDir = __DIR__ . '/../app';
		listFolders($appRootDir);
		$message = 'Úspěch. Operace hotova.';		
	} else {
		$message = 'Ne? Jak je libo.';		
	}	
	echo '<p><strong>' . $message . '</strong></p>';		
}

