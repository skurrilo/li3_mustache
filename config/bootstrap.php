<?php

use lithium\core\Libraries;
use lithium\net\http\Media;


// require dirname(__DIR__) . '/libraries/Mustache/Autoloader.php';
// Mustache_Autoloader::register();


require dirname(__DIR__) . '/libraries/mustache/Mustache.php';

Media::type('mustache', 'text/x-mustache', array(
	'view' => 'lithium\template\View',
	'loader' => 'Mustache',
	'renderer' => 'Mustache',
	'paths' => array(
		'template' => array(
			LITHIUM_APP_PATH . '/views/{:controller}/{:template}.{:type}.mustache',
			'{:library}/views/{:controller}/{:template}.{:type}.mustache',
		),
		'layout'   => array(
			LITHIUM_APP_PATH . '/views/layouts/{:layout}.{:type}.mustache',
			'{:library}/views/layouts/{:layout}.{:type}.mustache',
		),
		'element'  => array(
			LITHIUM_APP_PATH . '/views/elements/{:template}.{:type}.mustache',
			'{:library}/views/elements/{:template}.{:type}.mustache',
		),
	),
	'cache' => LITHIUM_APP_PATH . '/resources/tmp/cache/mustache',
));

?>