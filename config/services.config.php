<?php

namespace KJSencha;

use KJSencha\Frontend\Bootstrap;
use KJSencha\Direct\Remoting\Api\Factory\ModuleFactory;
use KJSencha\Direct\DirectManager;

use Zend\Cache\StorageFactory;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

return array(
    'aliases' => array(
        'kjsencha.api' => 'kjsencha.api.module',
    ),
    'factories' => array(
        'kjsencha.config' => 'KJSencha\Service\ModuleConfigurationFactory',

        'kjsencha.api.module' => 'KJSencha\Service\ModuleApiFactory',

        'kjsencha.annotationmanager' => function(ServiceLocatorInterface $sl) {
            $doctrineParser = new DoctrineAnnotationParser();
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Remotable');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Interval');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Formhandler');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Group');
            $annotationManager = new AnnotationManager();
            $annotationManager->attach($doctrineParser);

            return $annotationManager;
        },

        'kjsencha.modulefactory' => function(ServiceLocatorInterface $sl) {
            return new ModuleFactory($sl->get('kjsencha.annotationmanager'), $sl);
        },

        'kjsencha.cache' => function(ServiceLocatorInterface $sl) {
            $config = $sl->get('Config');
            $storage = StorageFactory::factory($config['kjsencha']['cache']);

            return $storage;
        },

        'kjsencha.bootstrap' => function(ServiceLocatorInterface $sl) {
            $config = $sl->get('Config');
            $bootstrap = new Bootstrap($config['kjsencha']['bootstrap']['default']);
            $bootstrap->addVariables(array(
                'App' => array(
                    'basePath' => $sl->get('Request')->getBasePath(),
                )
            ));
            $bootstrap->setDirectApi($sl->get('kjsencha.api'));

            return $bootstrap;
        },

        'kjsencha.direct.manager' => function(ServiceManager $sm) {
            $directManager = new DirectManager();
            $directManager->addPeeringServiceManager($sm);

            return $directManager;
        },
    )
);