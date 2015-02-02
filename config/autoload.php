<?php


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'postYou',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'postYou\BackgroundImageModel' => 'system/modules/background-image/classes/BackgroundImageModel.php',
	'postYou\MobileImageWizard'            => 'system/modules/background-image/classes/MobileImageWizard.php',
));
