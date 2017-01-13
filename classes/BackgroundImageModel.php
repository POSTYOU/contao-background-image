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

namespace postyou;


class BackgroundImageModel extends \Frontend
{
    function parseFrontendTemplateHook($strBuffer, $tmpName)
    {

         if (strpos($tmpName,"mod_article")!==false) {
            if (preg_match_all("/id=\"(.*?)\"/", $strBuffer, $res)) {
                if (!empty($res)) {
                    $alias = $res[1];
                    
                    /* Changes */
		    /* Hotfix for a syntax error when " given in the variable $alias[0] */
		    if(preg_match("/\"/",$alias[0])){
			$alias_0 = str_replace('"','\"',$alias[0]);
		    }else{
			$alias_0 = $alias[0];
		    }
					
		   /* changed the variable $alias[0] to $alias_0 */
                    $dbRes = $this->Database->prepare('SELECT id,addBackgroundImage,backgroundImageFilepath,backgroundImagePos FROM tl_article WHERE alias=? OR cssID REGEXP "(a:2:{i:0;s:)[0-9]+(:\")'.$alias_0.'(\")"')
                        ->execute($alias_0);
                                        
                    $dbRes = $dbRes->fetchAssoc();
                    if ($dbRes["addBackgroundImage"] == 1 && isset($dbRes["backgroundImageFilepath"])) {
                        $objFile = \FilesModel::findByPk($dbRes["backgroundImageFilepath"]);
                        if ($objFile !== null && is_file(TL_ROOT . '/' . $objFile->path)) {
                            $strBuffer=$this->insertSytleTag($objFile->path,deserialize($dbRes[backgroundImagePos],true),$strBuffer);
                        }
                    }
                }
            }
        }
        return $strBuffer;
    }

    function getContentElementHook($objElement, $strBuffer)
    {

        if ($objElement->row()["addBackgroundImage"] == '1' && isset($objElement->row()["backgroundImageFilepath"])) {
            $objFile = \FilesModel::findByPk($objElement->row()["backgroundImageFilepath"]);
            $pos=deserialize($objElement->row()["backgroundImagePos"],true);
            if ($objFile !== null && is_file(TL_ROOT . '/' . $objFile->path)) {
                $strBuffer=$this->insertSytleTag($objFile->path,$pos,$strBuffer);
            }

        }
        return $strBuffer;

    }

    function insertSytleTag($sourceFilePath,$pos,$strBuffer){
        if($GLOBALS['TL_CONFIG']['mobileBackgroundImage']===true)
            $resolutions=(unserialize($GLOBALS['TL_CONFIG']['imageList']));
        $path_parts = pathinfo($sourceFilePath);
        $rID=uniqid("_".$path_parts['filename']."_");

        $strBuffer = preg_replace("/class=\"/", "class=\"" .$rID." ", $strBuffer, 1);
        $tmpBuffer= "<style scoped='scoped' type='text/css'>\n.".$rID." {background-image:url(".$sourceFilePath.");\n";
        if(!empty($pos)){

            foreach($pos as $key=>$attribute){
                if(isset($attribute) && !empty($attribute)){
                    if($key==0 && (!empty($attribute) || !empty($pos[1])) && empty($pos[2])) {
                        $tmpBuffer .= "background-position:" . $attribute . " " . $pos[1] . ";\n";
                    }
                    if($key==2 && !empty($attribute))
                        $tmpBuffer.="background-position:".$attribute.";\n";
                    if($key==3)
                        $tmpBuffer.="background-repeat:".$attribute.";\n";
                    if($key==4)
                        $tmpBuffer.="background-attachment:".$attribute.";\n";
                }
            }
        }
        $tmpBuffer.="}";

        if(isset($resolutions) && is_array($resolutions) && !empty($resolutions)){ // mobile Versions are enabled and there is a list
            foreach($resolutions as $value) {
                if(!empty($value[1])){
                $objFile = new \File($path_parts['dirname'] . "/" . $value[2] . $path_parts['filename'] . $value[3] . "." . $path_parts['extension'],true);
                if ($objFile->exists()){ // file exists
                    $tmpBuffer .= "\n@media screen and (";
                    if ($value[0] == 1) {
                        $tmpBuffer .= "min";
                    } else {
                        $tmpBuffer .= "max";
                    }
                    $tmpBuffer .= "-width: " . $value[1] . "px){
                                    .".$rID." {background-image:url(" . $path_parts['dirname'] . "/" . $value[2] . $path_parts['filename'] . $value[3] . "." . $path_parts['extension'] . ")}
                                }";
                }
                }
            }

        }
        $tmpBuffer.='</style>';
        //return $tmpBuffer.$strBuffer;
        $strBuffer =preg_replace("/>/",">\n".$tmpBuffer,$strBuffer,1);
        return $strBuffer;
    }
    
    public function myAddCustomRegexp($strRegexp, $varValue, \Widget $objWidget)
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
    
}
