<?php

namespace KJSencha;

use KJSencha\View\Helper\ExtJS;
use KJSencha\View\Helper\Variables;
use KJSencha\View\Helper\LoaderConfig;
use KJSencha\View\Helper\DirectApi;
use Zend\ServiceManager\AbstractPluginManager;

return array(
    'factories' => array(
        'extJs' => function(AbstractPluginManager $pluginManager) {
            $config = $pluginManager->getServiceLocator()->get('config');

            return new ExtJS(
                $config['kjsencha']['library_path'],
                $pluginManager->get('headLink'),
                $pluginManager->get('headScript')
            );
        },
        'kjSenchaVariables' => function(AbstractPluginManager $pluginManager) {
            return new Variables(
                $pluginManager->get('headScript'),
                $pluginManager->getServiceLocator()->get('kjsencha.bootstrap')
            );
        },
        'kjSenchaLoaderConfig' => function(AbstractPluginManager $pluginManager) {
            return new LoaderConfig(
                $pluginManager->get('basePath'),
                $pluginManager->get('headScript'),
                $pluginManager->getServiceLocator()->get('kjsencha.bootstrap')
            );
        },
        'kjSenchaDirectApi' => function(AbstractPluginManager $pluginManager) {
            return new DirectApi(
                $pluginManager->get('headScript'),
                $pluginManager->getServiceLocator()->get('kjsencha.bootstrap')
            );
        },
    )
);