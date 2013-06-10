<?php

namespace KJSencha;

use Zend\ServiceManager\AbstractPluginManager;

return array(
    'factories' => array(
        'kjsencha_direct' => function(AbstractPluginManager $pluginManager) {
            $sl = $pluginManager->getServiceLocator();
            $config = $sl->get('Config');

            /* @var $manager \KJSencha\Direct\DirectManager */
            $manager = $sl->get('kjsencha.direct.manager');
            /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Api */
            $apiFactory = $sl->get('kjsencha.api');

            $controller = new Controller\DirectController($manager, $apiFactory);
            $controller->setDebugMode($config['kjsencha']['debug_mode']);

            return $controller;
        },
        'kjsencha_data' => function(AbstractPluginManager $pluginManager) {
            $sl = $pluginManager->getServiceLocator();
            /* @var $componentManager \KJSencha\Service\ComponentManager */
            $componentManager = $sl->get('kjsencha.componentmanager');
            return new Controller\DataController($componentManager);
        },
    )
);