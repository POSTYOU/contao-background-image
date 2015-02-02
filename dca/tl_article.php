<?php

// CSS for layout of file-field
if (TL_MODE == 'BE')
    $GLOBALS['TL_CSS'][] = 'system/modules/background-image/assets/css/backend.css|screen';


$GLOBALS['TL_DCA']['tl_article']['palettes']['default'] =
    preg_replace('/;/', ';{backgroundImage_legend},addBackgroundImage;', $GLOBALS['TL_DCA']['tl_article']['palettes']['default'],1);

// add Selector
$GLOBALS['TL_DCA']['tl_article']['palettes']['__selector__'][] = 'addBackgroundImage';

// add Subpalettes
$GLOBALS['TL_DCA']['tl_article']['subpalettes']['addBackgroundImage'] = 'backgroundImageFilepath';

// Add fields
$GLOBALS['TL_DCA']['tl_article']['fields']['addBackgroundImage'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_article']['addBackgroundImage'],
    'exclude'			=> true,
    'inputType'			=> 'checkbox',
    'eval'				=> array('submitOnChange'=>true, 'tl_class' => 'clr w50'),
    'sql'				=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_article']['fields']['backgroundImageFilepath'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_article']['backgroundImageFilepath'],
    'exclude'	=> true,
    'inputType'	=> 'fileTree',
    'explanation'	=> 'backgroundImageFilepath',
    'eval'	=> array('filesOnly'=>true, 'fieldType'=>'radio', 'extensions' =>'ico,jpg,jpeg,png,gif', 'mandatory'=>true, 'tl_class'=>'w50 background_image'),
    'sql'	=> "binary(16) NULL"
);