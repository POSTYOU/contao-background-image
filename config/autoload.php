<?php


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'postyou',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'postyou\BackgroundImageModel' => 'system/modules/background-image/classes/BackgroundImageModel.php',
	'postyou\MobileImageWizard'            => 'system/modules/background-image/classes/MobileImageWizard.php',
));
