<?php

if (TL_MODE == 'FE') {
    $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array(
        'postyou\BackgroundImageModel',
        'parseFrontendTemplateHook'
    );

    $GLOBALS['TL_HOOKS']['getContentElement'][] = array('postyou\BackgroundImageModel', 'getContentElementHook');

}

$GLOBALS['TL_CONFIG']['bg-image']['fieldNr']="4";

$GLOBALS['BE_FFL']['mobileImageWizard'] = 'postyou\MobileImageWizard';

$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('postyou\BackgroundImageModel', 'myAddCustomRegexp');
