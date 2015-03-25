<?php

if (TL_MODE == 'FE') {
    $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array(
        'postYou\\BackgroundImageModel',
        'parseFrontendTemplateHook'
    );

    $GLOBALS['TL_HOOKS']['getContentElement'][] = array('postyou\\BackgroundImageModel', 'getContentElementHook');

}

$GLOBALS['BE_FFL']['mobileImageWizard'] = 'MobileImageWizard';

$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('My_tl_content', 'myAddCustomRegexp');
