<?php

namespace KJSencha;

use Zend\Cache\StorageFactory;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;

return array(
    'aliases' => array(
        'extjsconfig' => 'kjsencha.config',
        'kjsencha.api' => 'kjsencha.api.module',
    ),
    'factories' => array(
        'kjsencha.config' => 'KJSencha\Service\ModuleConfigurationFactory',
        'kjsencha.api.module' => 'KJSencha\Service\ModuleApiFactory',
        /**
         * Annotation Parser
         */
        'kjsencha.annotationmanager' => function($sm) {
            $doctrineParser = new DoctrineAnnotationParser;
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Remotable');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Interval');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Formhandler');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Group');
            $annotationManager = new AnnotationManager;
            $annotationManager->attach($doctrineParser);
            return $annotationManager;
        },
        /**
         * Module Direct API
         */
        'kjsencha.modulefactory' => function($sm) {
            $moduleFactory = new Direct\Remoting\Api\Factory\ModuleFactory;
            $moduleFactory->setAnnotationManager($sm->get('kjsencha.annotationmanager'));
            return $moduleFactory;
        },
        /**
         * Cache
         */
        'kjsencha.cache' => function($sm) {
            $config = $sm->get('Config');
            $storage = StorageFactory::factory($config['kjsencha']['cache']);
            return $storage;
        },
        'kjsencha.bootstrap' => function($sm) {
            $config = $sm->get('Config');
            $bootstrap = new Frontend\Bootstrap($config['kjsencha']['bootstrap']['default']);
            $bootstrap->addVariables(array(
                'App' => array(
                    'basePath' => $sm->get('Request')->getBasePath(),
                )
            ));
            $bootstrap->setDirectApi($sm->get('kjsencha.api'));
            return $bootstrap;
        },
        'kjsencha.direct.manager' => function($sm) {
            $directManager = new Direct\DirectManager();
            $directManager->addPeeringServiceManager($sm);
            return $directManager;
        },
        /**
         * Component Manager
         */
        'kjsencha.cmpmgr' => function($sm) {
            $config = $sm->get('Config');
            $serviceConfig = new \Zend\Mvc\Service\ServiceManagerConfig($config['kjsencha.components']);
            $componentManager = new Service\ComponentManager($serviceConfig);
            $componentManager->addPeeringServiceManager($sm);
            return $componentManager;
        }
    )
);