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
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['addBackgroundImage'] = 'backgroundImageFilepath,backgroundImagePos,backgroundImagePos2,backgroundImagePosTXT,backgroundImageRepeat,backgroundImageAttachment';

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

$GLOBALS['TL_DCA']['tl_content']['fields']['backgroundImagePos'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_content']['backgroundImagePos'],
    'default'			=> "",
    'inputType'			=> 'select',
    'options_callback' =>  array("my_tl_content","getPosOptns1"),
    'eval'				=> array("doNotSaveEmpty"=>true,'tl_class' => 'w50 tl_new_short'),
    'save_callback'     => array(array("My_tl_content","saveAll")),
    'load_callback'     => array(array("My_tl_content","loadPos1")),
    'sql'				=> "char(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['backgroundImagePos2'] = array
(
    'label'				=> " ",
//    'default'			=> "", if enabled db error when creating new
    'inputType'			=> 'select',
    'options_callback' =>  array("my_tl_content","getPosOptns2"),
    'eval'				=> array("doNotSaveEmpty"=>true,'tl_class' => 'w50 tl_new_short tl_scnd_short'),
    'load_callback'         =>array(function($varValue,$dc){
        $fieldName="backgroundImagePos2";
        if(isset($_POST[$fieldName]) && !empty($_POST[$fieldName]))
            return \Input::post($fieldName);
        elseif(isset($dc->{$fieldName}))
            return $dc->{$fieldName};
    }),
    'save_callback'     => array(function($varValue,$dc){
        return "";
    })
);

$GLOBALS['TL_DCA']['tl_content']['fields']['backgroundImagePosTXT'] = array
(
    'label'	=> &$GLOBALS['TL_LANG']['tl_content']['backgroundImagePosTXT'],
    'inputType'			=> 'text',
    'eval'				=> array("rgxp"=>"px","doNotSaveEmpty"=>true,'tl_class' => 'clr w50'),
    'load_callback'         =>array(function($varValue,$dc){
        $fieldName="backgroundImagePosTXT";
        if(isset($_POST[$fieldName]) && !empty($_POST[$fieldName]))
            return \Input::post($fieldName);
        elseif(isset($dc->{$fieldName}))
            return $dc->{$fieldName};
    }),
    'save_callback'     => array(function($varValue,$dc){
        return "";
    })
);

$GLOBALS['TL_DCA']['tl_content']['fields']['backgroundImageRepeat'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_content']['backgroundImageRepeat'],
//    'default'			=> "", if enabled db error when creating new
    'inputType'			=> 'select',
    'options_callback' =>  array("my_tl_content","getRepeatOptns"),
    'eval'				=> array("doNotSaveEmpty"=>true,'tl_class' => 'clr w50'),
    'load_callback'         =>array(function($varValue,$dc){
        $fieldName="backgroundImageRepeat";
        if(isset($_POST[$fieldName]) && !empty($_POST[$fieldName]))
            return \Input::post($fieldName);
        elseif(isset($dc->{$fieldName}))
            return $dc->{$fieldName};
    }),
    'save_callback'     => array(function($varValue,$dc){
        return "";
    })

);

$GLOBALS['TL_DCA']['tl_content']['fields']['backgroundImageAttachment'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_content']['backgroundImageAttachment'],
//    'default'			=> "", if enabled db error when creating new
    'inputType'			=> 'select',
    'options_callback' =>  array("my_tl_content","getAttachmentOptns"),
    'eval'				=> array("doNotSaveEmpty"=>true,'tl_class' => 'clr w50'),
    'load_callback'         =>array(function($varValue,$dc){
        $fieldName="backgroundImageAttachment";
        if(isset($_POST[$fieldName]) && !empty($_POST[$fieldName]))
            return \Input::post($fieldName);
        elseif(isset($dc->{$fieldName}))
            return $dc->{$fieldName};
    }),
    'save_callback'     => array(function($varValue,$dc){
       return "";
    })

);



Class My_tl_content{
//nach oben background-color (Hintergrundfarbe)
//nach oben background-image (Hintergrundbild)

function loadPos1($varValue,$dc){
    $out=deserialize($varValue,true);
    $dc->backgroundImagePos2=$out[1];
    $dc->backgroundImagePosTXT=$out[2];
    $dc->backgroundImageRepeat=$out[3];
    $dc->backgroundImageAttachment=$out[4];

    return $out[0];

}

    public function myAddCustomRegexp($strRegexp, $varValue, Widget $objWidget)
    {
        if ($strRegexp == 'px')
        {
            if (!preg_match('/^((\d{1,3}px) (\d{1,3}px)|(\d{1,3}%) (\d{1,3}%))$/', $varValue))
            {
                $objWidget->addError('Field ' . $objWidget->label . ' muss zwei Pixel oder Prozent-Werte enthalten');
            }

            return true;
        }

        return false;
    }

function saveAll($varValue,$dc){
    $pos1=$varValue;
    $pos2=Input::post('backgroundImagePos2');
    $posTxt=Input::post('backgroundImagePosTXT');
    $rep=Input::post('backgroundImageRepeat');
    $att=Input::post('backgroundImageAttachment');

    $in=array($pos1,$pos2,$posTxt,$rep,$att);
//    var_dump($in);
    return serialize($in);
}

 function getRepeatOptns(){
     return array("","repeat","repeat-x","repeat-y","no-repeat");
 }
    function getAttachmentOptns(){
        return array("","scroll","fixed");
    }
    function getPosOptns1(){
        return array("","center","left","right");
    }
    function getPosOptns2(){
        return array("","top","bottom","center");
    }
//nach oben background-repeat (Wiederholungs-Effekt)
//nach oben background-attachment (Wasserzeichen-Effekt)
//nach oben background-position (Hintergrundposition)

}