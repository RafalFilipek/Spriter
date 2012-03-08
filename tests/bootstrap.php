<?php

$loader = require __DIR__.'/../vendor/.composer/autoload.php';

$loader->add('Spriter', __DIR__.'/../src');

/*

spl_autoload_register(function($class)
{
	$path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	foreach (array('tests') as $dirPrefix) {
		$file = __DIR__.'/../'.$dirPrefix.'/'.$path.'.php';
		if (file_exists($file)) {
			require_once $file;
			return true;
		}
	}
});

*/
