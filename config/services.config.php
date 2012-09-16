<?php

namespace KJSencha;

use Zend\Cache\StorageFactory;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;

return array(
    'aliases' => array(
        'extjsconfig'     => 'kjsencha.config',
        'kjsencha.api'   => 'kjsencha.api.module',
    ),
    'factories' => array(
        'kjsencha.config' => 'KJSencha\Service\ModuleConfigurationFactory',
        'kjsencha.api.module' => 'KJSencha\Service\ModuleApiFactory',
        'kjsencha.annotation_parser' => function($sm) {
            $annotationManager = new AnnotationManager;
            $doctrineParser = new DoctrineAnnotationParser;
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Remotable');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Interval');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Formhandler');
            $doctrineParser->registerAnnotation('KJSencha\Annotation\Group');
            $annotationManager->attach($doctrineParser);

            return $annotationManager;
        },
        'kjsencha.modulefactory' => function($sm) {
            $moduleFactory = $sm->get('KJSencha\Direct\Remoting\Api\Factory\ModuleFactory');
            $moduleFactory->setAnnotationManager($sm->get('kjsencha.annotation_parser'));
            return $moduleFactory;
        },
        'kjsencha.cache' => function($sm) {
            $config = $sm->get('Config');
            $storage = StorageFactory::factory($config['kjsencha']['cache']);
            return $storage;
        },
    )
);