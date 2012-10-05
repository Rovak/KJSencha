<?php

namespace KJSencha;

use KJSencha\Controller\DirectController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;

return array(
    'factories' => array(
        'kjsencha_direct' => function(AbstractPluginManager $pluginManager)
        {
            $sl = $pluginManager->getServiceLocator();
            return new DirectController($sl->get('kjsencha.direct.manager'));
        },
    )
);