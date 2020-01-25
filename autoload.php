<?php
require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

spl_autoload_register("gbStandardAutoload");

function gbStandardAutoload($className)
{
	$dirs = [
		'controller',
		'data/migrate',
		'lib',
		'lib/smarty',
		'lib/commands',
		'model'
	];
	$found = false;
	foreach ($dirs as $dir) {
		$fileName = __DIR__ . '/' . $dir . '/' . $className . '.class.php';
		if (is_file($fileName)) {

			require_once($fileName);
			$found = true;
		}
		
	}
	//$obj = new A;

	if (!$found) {
		throw new Exception('Unable to load ' . $className);
	}
	return true;
}


//$object = new Test;
