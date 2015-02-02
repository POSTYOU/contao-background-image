<?php

if (TL_MODE == 'FE') {
    $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array(
        'postYou\\BackgroundImageModel',
        'parseFrontendTemplateHook'
    );

    $GLOBALS['TL_HOOKS']['getContentElement'][] = array('postYou\\BackgroundImageModel', 'getContentElementHook');

}

$GLOBALS['BE_FFL']['mobileImageWizard'] = 'MobileImageWizard';
