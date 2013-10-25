<?php

use lithium\net\http\Media;

use lithium\core\Libraries;

// Libraries::add('Handlebars', array(
//     // "prefix" => "Handlebars_",
//     // "includePath" => LITHIUM_LIBRARY_PATH, // or LITHIUM_APP_PATH . '/libraries'
//     // "bootstrap" => "Loader/Autoloader.php",
//     // "loader" => array("Handlebars", "register"),
//     // "transform" => function($class) { return str_replace("_", "/", $class) . ".php"; }
// ));

require dirname(__DIR__) . '/libraries/Handlebars/Autoloader.php';
Handlebars_Autoloader::register();

Media::type('mustache', 'text/x-mustache', array(
	'view' => 'lithium\template\View',
	'loader' => 'Mustache',
	'renderer' => 'Mustache',
	'paths' => array(
		'template' => '{:library}/views/{:controller}/{:template}.{:type}',
		'layout'   => '{:library}/views/layouts/{:layout}.{:type}',
		'element'  => '{:library}/views/elements/{:template}.{:type}'
	)
));

?>