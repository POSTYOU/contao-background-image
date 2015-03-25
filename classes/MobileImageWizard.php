<?php

namespace postYou;


class MobileImageWizard extends \Widget{


    private $inputFieldsNumber=2;
    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';
    /**
     * Add specific attributes
     * @param string
     * @param mixed
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey)
        {
            case 'maxlength':
                if ($varValue > 0)
                {
                    $this->arrAttributes['maxlength'] = $varValue;
                }
                break;
            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }
    /**
     * Generate the widget and return it as string
     * @return string
     */
    public function generate()
    {


                echo"<script>

window.addEvent('domready', function() {
   new Sortables('#ctrl_imageList', {
            contstrain: true,
            opacity: 0.2,
            handle: '.drag-handle'
        });
  });

//window.onload = function() {
// alert(\"helo\");
//}
//$( document ).ready(function() {
//    alert(\"helo\");
//    new Sortables('#ctrl_imageList', {
//            contstrain: true,
//            opacity: 0.6,
//            handle: '.drag-handle'
//        });
//});
    /**
     * List wizard
     *
     * @param {object} el The DOM element
     * @param {string} command The command name
     * @param {string} id The ID of the target element
     */
    function myListWizard(el, command, id, name)
    {

        var list = $(id),
            parent = $(el).getParent('li'),
            items = list.getChildren(),
            tabindex = list.get('data-tabindex'),
            input, previous, next, rows, i,j;
        Backend.getScrollOffset();
        switch (command) {
            case 'copy':
                var clone = parent.clone(true).inject(parent, 'before');
                if (input = parent.getFirst('input')) {
                var elLength = parent.childNodes.length;
                    for (i=0; i<elLength; i++)
                    {
                    if(parent.childNodes[i].nodeName=='INPUT' || parent.childNodes[i].nodeName=='SELECT' ){
                            clone.childNodes[i].value = parent.childNodes[i].value;
                        }
                    }
                }
                break;
            case 'up':
                if (previous = parent.getPrevious('li')) {
                    parent.inject(previous, 'before');
                } else {
                    parent.inject(list, 'bottom');
                }
                break;
            case 'down':
                if (next = parent.getNext('li')) {
                    parent.inject(next, 'after');
                } else {
                    parent.inject(list.getFirst('li'), 'before');
                }
                break;
            case 'delete':
                if (items.length > 1) {
                    parent.destroy();
                } else {
                    lastOne=list.getChildren()[0];
                    child_Length=lastOne.childNodes.length;
                    for (i=0; i<child_Length; i++)
                    {
                    if(lastOne.childNodes[i].nodeName=='INPUT')
                        lastOne.childNodes[i].set('value', '');
                    }

                }
                break;
        }
        rows = list.getChildren();
        for (i=0; i<rows.length; i++) {
             var elLength = rows[i].childNodes.length;
             var textFieldsNumber=0;
             for (j=0; j<elLength; j++)
                    {
                    if(rows[i].childNodes[j].nodeName=='INPUT' || rows[i].childNodes[j].nodeName=='SELECT'){
                            rows[i].childNodes[j].set('tabindex', i+1)
                            rows[i].childNodes[j].name = name+'['+i+']'+'['+textFieldsNumber+']';

                            textFieldsNumber++;
                        }
                    }
        }
        new Sortables(list, {
            contstrain: true,
            opacity: 0.4,
            handle: '.drag-handle'
        });

}
</script>";

//        $arrButtons = array('copy', 'drag', 'up', 'down', 'delete');
        $arrButtons = array('copy', 'delete','drag');
        $strCommand = 'cmd_' . $this->strField;

        // Change the order
        if (\Input::get($strCommand) && is_numeric(\Input::get('cid')) && \Input::get('id') == $this->currentRecord)
        {

            $this->import('Database');

            switch (\Input::get($strCommand))
            {
                case 'copy':
                    $this->varValue = $this->duplicate($this->varValue, \Input::get('cid'));
                    break;

                case 'up':
                    $this->varValue = array_move_up($this->varValue, \Input::get('cid'));
                    break;

                case 'down':
                    $this->varValue = array_move_down($this->varValue, \Input::get('cid'));
                    break;

                case 'delete':
                    $this->varValue = array_delete($this->varValue, \Input::get('cid'));
                    break;
            }

////            $this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
////                ->execute(serialize($this->varValue), $this->currentRecord);
//
            if (\Input::post('FORM_SUBMIT') == $this->strTable){
                error_log(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', \Environment::get('request'))));
             $this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', \Environment::get('request'))));
            }
        }

        // Make sure there is at least an empty array
        if (!is_array($this->varValue) || empty($this->varValue))
        {
            $this->varValue = array(array(0,'','',''));
        }



// Initialize the tab index
        if (!\Cache::has('tabindex'))
        {
            \Cache::set('tabindex', 1);
        }
        $tabindex = \Cache::get('tabindex');

        $return = "<div class='tl_mobileImageWizard_wrapper'>";
        $return .= "<span class='tl_short'>".$GLOBALS['TL_LANG']['tl_settings']['column1']."</span>";
        $return .= "<span class='tl_short'>".$GLOBALS['TL_LANG']['tl_settings']['column2']."</span>";
        $return .= "<span class='tl_short'>".$GLOBALS['TL_LANG']['tl_settings']['column3']."</span>";
        $return .= "<span class='tl_short'>".$GLOBALS['TL_LANG']['tl_settings']['column4']."</span>";
        $return .= '<ul id="ctrl_'.$this->strId.'" class="tl_mobileImageWizard" data-tabindex="'.$tabindex.'">';
// Add input fields

//        echo ("<pre>");
//        var_dump($this->varValue);
//        echo ("</pre>");


        foreach ($this->varValue as $key=>$value)
        {
            $return .= '<li>';

            for($i=0; $i<count($value); $i++) {

                if($i==0){
                    $return.='<select name="'.$this->strId .'['.$key.']['.$i.']'.'" class="tl_short">';
//                     name='".$this->strId."[".$key."][".$i."]"."' class='tl_short'
//                    tabindex='".$tabindex ."'
//                    >";
                    if($value[$i]==1) {
                        $return .= "<option value='0'>max-width</option>";
                        $return .= "<option selected value='1'>min-width</option>";
                    }else{
                        $return .= "<option selected value='0'>max-width</option>";
                        $return .= "<option value='1'>min-width</option>";
                    }

                    $return.="</select>";
                }else{

                $return .= '<input type="text" name="'.$this->strId .'['.$key.']['.$i.']'.'" class="';
//                if($i==2)
//                    $return .= 'tl_long" ';
//                else
                    $return .= 'tl_short" ';
                $return .= 'tabindex="'.$tabindex . '" value="' . specialchars($value[$i]) . '"' . $this->getAttributes() . '/> ';
            }
            }
// Add buttons
            foreach ($arrButtons as $button)
            {
                $class = ($button == 'up' || $button == 'down') ? ' class="button-move"' : '';
                if ($button == 'drag')
                {
                    $return .= \Image::getHtml('drag.gif', '', 'class="drag-handle" title="'.sprintf($GLOBALS['TL_LANG']['MSC']['move']).'" style="top:3px;"');
                }
                else
                {

//                    $return .= '<div ';
//                    $return .= $class . ' title="'.specialchars($GLOBALS['TL_LANG']['MSC']['lw_'.$button]).'" onclick="myListWizard(this,\''.$button.'\',\'ctrl_'.$this->strId.'\')return false;">';
                      $return .=\Image::getHtml($button.'.gif', $GLOBALS['TL_LANG']['MSC']['lw_'.$button], 'class="tl_listwizard_img" onclick="myListWizard(this,\''.$button.'\',\'ctrl_'.$this->strId.'\',\''.$this->strId.'\')"');
//                    $return .='</div> ';
                }
            }
            $return .= '</li>';
            $tabindex++;
        }
// Store the tab index
        \Cache::set('tabindex', $tabindex);
//        $return.="</div>";
        return $return.'
</ul></div>';





    }

    function duplicate($arrStack, $intIndex)
    {
        $arrBuffer = array();

        foreach($arrStack as $key=>$value){
            if($key>=$intIndex){
                $arrBuffer[$key+1]=$value;
            }
            if($key<=$intIndex)
              $arrBuffer[$key]=$value;
    }

//        for ($i=0; $i<=$intIndex; $i++)
//        {
//            $arrStack[] = $arrBuffer[$i];
//        }
//
//        for ($i=$intIndex, $c=count($arrBuffer); $i<$c; $i++)
//        {
//            $arrStack[] = $arrBuffer[$i];
//        }

        return $arrBuffer;
    }


}
