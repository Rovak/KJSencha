<?php

namespace KJSencha;

use KJSencha\Direct\Remoting\Api\Factory\ApiBuilder;
use KJSencha\Direct\DirectManager;
use KJSencha\Service\TestEchoService;
use KJSencha\Frontend\Bootstrap;
use KJSencha\Service\ComponentManager;
use Zend\Cache\StorageFactory;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

return array(
    'factories' => array(

         * Produces a \KJSencha\Direct\Remoting\Api instance consumed by
         * the RPC services
         */
        'kjsencha.api' => 'KJSencha\Service\ApiFactory',
        /**
         * Annotation manager used to discover features available for the RPC services
         */
        'kjsencha.annotationmanager' => function() {
            $doctrineParser = new DoctrineAnnotationParser();
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Remotable');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Interval');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Formhandler');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Group');
            $annotationManager = new AnnotationManager();
            $annotationManager->attach($doctrineParser);
            return $annotationManager;
        },

        /**
         * Factory responsible for crawling module dirs and building APIs
         */
        'kjsencha.apibuilder' => function(ServiceLocatorInterface $sl) {
            /* @var $annotationManager AnnotationManager */
            $annotationManager = $sl->get('kjsencha.annotationmanager');
            /* @var $directManager DirectManager */
            $directManager = $sl->get('kjsencha.direct.manager');

            return new ApiBuilder($annotationManager, $directManager);
        },

        /**
         * Cache where the API will be stored once it is filled with data
         */
        'kjsencha.cache' => function(ServiceLocatorInterface $sl) {
            $config = $sl->get('Config');
            $storage = StorageFactory::factory($config['kjsencha']['cache']);
            return $storage;
        },
        /**
         * Bootstrap service that allows rendering of the API into an output that the
         * ExtJs direct manager can understand
         */
        'kjsencha.bootstrap' => function(ServiceLocatorInterface $sl) {
            $config = $sl->get('Config');
            $bootstrap = new Bootstrap($config['kjsencha']['bootstrap']['default']);
            $bootstrap->addVariables(array(
                'App' => array(
                    'basePath' => $sl->get('Request')->getBasePath(),
                )
            ));
            /* @var $directApi \KJSencha\Direct\Remoting\Api\Api */
            $directApi = $sl->get('kjsencha.api');
            $bootstrap->setDirectApi($directApi);

            return $bootstrap;
        },

        /**
         * Direct manager, handles instantiation of requested services
         */
        'kjsencha.direct.manager' => function(ServiceManager $sm) {
            $directManager = new DirectManager();
            $directManager->addPeeringServiceManager($sm);

            return $directManager;
        },

        /**
         * Echo service - registered by default with ExtJs's remoting provider to allow
         * simple verification that the module's features are active and working.
         */
        'kjsencha.echo' => function() {
            return new TestEchoService('Hello ');
        },

        'kjsencha.cmpmgr' => function($sm) {
            $config = $sm->get('Config');
            $serviceConfig = new ServiceManagerConfig($config['kjsencha']['components']);
            $componentManager = new ComponentManager($serviceConfig);
            $componentManager->addPeeringServiceManager($sm);
            return $componentManager;
        }
    )
);