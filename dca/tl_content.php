<?php

// CSS für Anzeige im Backend einbinden
if (TL_MODE == 'BE')
    $GLOBALS['TL_CSS'][] = 'system/modules/background-image/assets/css/backend.css|screen';


foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $key=>$palette){ //alle Bereiche (Paletten) durchgehen
    if(!is_array($palette) && is_string($palette)) {
//        if (strpos($palette, "type;")) {
            $GLOBALS['TL_DCA']['tl_content']['palettes'][$key] = preg_replace('/;/', ';{backgroundImage_legend},addBackgroundImage;', $GLOBALS['TL_DCA']['tl_content']['palettes'][$key],1);
//        }
    }
}

// add Selector
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'addBackgroundImage';

// add Subpalettes
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['addBackgroundImage'] = 'backgroundImageFilepath';

// Add fields
$GLOBALS['TL_DCA']['tl_content']['fields']['addBackgroundImage'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_content']['addBackgroundImage'],
    'exclude'			=> true,
    'inputType'			=> 'checkbox',
    'eval'				=> array('submitOnChange'=>true, 'tl_class' => 'clr w50'),
    'sql'				=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['backgroundImageFilepath'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_content']['backgroundImageFilepath'],
    'exclude'	=> true,
    'inputType'	=> 'fileTree',
    'explanation'	=> 'backgroundImageFilepath',
    'eval'	=> array('filesOnly'=>true, 'fieldType'=>'radio', 'extensions' =>'ico,jpg,jpeg,png,gif', 'mandatory'=>true, 'tl_class'=>'w50 background_image'),
    'sql'	=> "binary(16) NULL"
);

