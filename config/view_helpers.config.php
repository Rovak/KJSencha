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
            $config = $pluginManager->getServiceLocator()->get('Config');
            /* @var $headLink \Zend\View\Helper\HeadLink */
            $headLink = $pluginManager->get('headLink');
            /* @var $headScript \Zend\View\Helper\HeadScript */
            $headScript = $pluginManager->get('headScript');

            return new ExtJS($config['kjsencha'], $headLink, $headScript);
        },
        'kjSenchaVariables' => function(AbstractPluginManager $pluginManager) {
            /* @var $headScript \Zend\View\Helper\HeadScript */
            $headScript = $pluginManager->get('headScript');
            /* @var $bootstrap \KJSencha\Frontend\Bootstrap */
            $bootstrap = $pluginManager->getServiceLocator()->get('kjsencha.bootstrap');

            return new Variables($headScript, $bootstrap);
        },
        'kjSenchaLoaderConfig' => function(AbstractPluginManager $pluginManager) {
            /* @var $basePath \Zend\View\Helper\BasePath */
            $basePath = $pluginManager->get('basePath');
            /* @var $headScript \Zend\View\Helper\HeadScript */
            $headScript = $pluginManager->get('headScript');
            /* @var $bootstrap \KJSencha\Frontend\Bootstrap */
            $bootstrap = $pluginManager->getServiceLocator()->get('kjsencha.bootstrap');

            return new LoaderConfig($basePath, $headScript, $bootstrap);
        },
        'kjSenchaDirectApi' => function(AbstractPluginManager $pluginManager) {
            /* @var $headScript \Zend\View\Helper\HeadScript */
            $headScript = $pluginManager->get('headScript');
            /* @var $bootstrap \KJSencha\Frontend\Bootstrap */
            $bootstrap = $pluginManager->getServiceLocator()->get('kjsencha.bootstrap');

            return new DirectApi($headScript, $bootstrap);
        },
    )
);