<?php
/**
 * Backgroundimage
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2015 POSTYOU
 *
 * @package background-image
 * @author  Gerald Meier
 * @link    http://www.postyou.de
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
 
if (TL_MODE == 'BE')
    $GLOBALS['TL_CSS'][] = 'system/modules/background-image/assets/css/backend.css|screen';


   /**
    * Palettes
    */
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .=
        ';{backgroundImage_legend:hide},mobileBackgroundImage';

// add Selector
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'mobileBackgroundImage';

// add Subpalettes
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['mobileBackgroundImage'] = 'imageList';

// Add fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['mobileBackgroundImage'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_settings']['mobileBackgroundImage'],
    'exclude'			=> true,
    'inputType'			=> 'checkbox',
    'eval'				=> array('submitOnChange'=>true, 'tl_class' => 'clr w50'),
    'sql'				=> "char(1) NOT NULL default ''"
);

    /**
     * Fields
     */
    $GLOBALS['TL_DCA']['tl_settings']['fields']['imageList'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['listitems'],
        'exclude'                 => true,
        'inputType'               => 'mobileImageWizard',
        'eval'                    => array('tl_class' => 'clr'),
        'save_callback' => array
        (
            array('my_tl_settings', 'saveList')
        ),
        'sql'                     => "blob NULL"

    );

class my_tl_settings extends tl_settings
{

    public function saveList($varValue,$dc){
        $list=unserialize($varValue);
        $fieldNr=$GLOBALS['TL_CONFIG']['bg-image']['fieldNr'];

        $temp=trim(implode("",array_values($list[0])));
        if(empty($temp)){
            return "";
        }
        
        if(!empty($list) && count($list[0])<$fieldNr)
            for($i=($fieldNr-count($list));$i<$fieldNr;$i++)
                $list[]="";
            $varValue=serialize($this->sortList($list));
        return $varValue;
    }

    private function sortList($list){
        $proxyListMax=array();
        $proxyListMin=array();
        $proxyList=array();
        $resList=array();
            foreach($list as $key=>$value){
                if($value[0]==1)
                    $proxyListMin[$value[1]]=$key;
                else
                    $proxyListMax[$value[1]]=$key;
            }
        if(count($proxyListMax)>0) {
            krsort($proxyListMax);
            $proxyList=$proxyListMax;
        }
        if(count($proxyListMin)>0){
            ksort($proxyListMin);
            $proxyList=array_merge($proxyList,$proxyListMin);
        }
        foreach($proxyList as $key=>$value){
            $resList[]=$list[$value];
        }
        return $resList;
    }

}
