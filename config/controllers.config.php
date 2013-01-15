<?php

namespace KJSencha;

use Zend\ServiceManager\AbstractPluginManager;

return array(
    'invokables' => array(
        'kjsencha_data' => 'KJSencha\Controller\DataController',
    ),
    'factories' => array(
        'kjsencha_direct' => function(AbstractPluginManager $pluginManager) {
            $sl = $pluginManager->getServiceLocator();

            /* @var $manager \KJSencha\Direct\DirectManager */
            $manager = $sl->get('kjsencha.direct.manager');
            /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Api */
            $apiFactory = $sl->get('kjsencha.api');

            return new Controller\DirectController($manager, $apiFactory);
        },
        'kjsencha_data' => function(AbstractPluginManager $pluginManager) {
            /* @var $componentManager \KJSencha\Service\ComponentManager */
            $componentManager = $pluginManager->get('kjsencha.componentmanager');
            return new Controller\DataController($componentManager);
        },
    )
);