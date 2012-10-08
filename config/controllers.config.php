<?php

namespace KJSencha;

use KJSencha\Controller\DirectController;
use Zend\ServiceManager\AbstractPluginManager;

return array(
    'factories' => array(
        'kjsencha_direct' => function(AbstractPluginManager $pluginManager)
        {
            $sl = $pluginManager->getServiceLocator();

            /* @var $manager \KJSencha\Direct\DirectManager */
            $manager = $sl->get('kjsencha.direct.manager');
            /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Factory\AbstractFactory */
            $apiFactory = $sl->get('kjsencha.api');

            return new DirectController($manager, $apiFactory);
        },
    )
);