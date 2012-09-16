<?php

namespace KJSencha;

use KJSencha\Direct\Polling\TaskApi;
use Zend\Cache\StorageFactory;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;

return array(
    'shared' => array(
//                'kjsencha.annotation_parser' => FALSE,
    ),
    'aliases' => array(
        'extjsconf'     => 'kjsencha.config',
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
        'kjsencha.task.api' => function($sm) {
            $config = $sm->get('Config');

            $taskApi = new Direct\Polling\TaskApi;
            /* @var $moduleFactory TaskApi */
            $taskApi->setAnnotationManager($sm->get('kjsencha.annotation_parser'));

            // Config
            if (isset($config['kjsencha']['polling'])) {
                foreach ($config['kjsencha']['polling']['modules'] as $dir) {
                    $taskApi->addDirectory($dir['directory']);
                }
            }
            $cachedTaskApi = new Direct\Polling\CachedTaskApi($taskApi, $sm->get('kjsencha.cache'));

            return $cachedTaskApi;
        },
        'kjsencha.task.runner' => function($sm) {
            $taskRunner = new Direct\Polling\TaskRunner($sm->get('kjsencha.task.api'));
            // Config
            return $taskRunner;
        },
        'kjsencha.cache' => function($sm) {
            $config = $sm->get('Config');
            $storage = StorageFactory::factory($config['kjsencha']['cache']);

            return $storage;
        },
        'kjsencha.direct.dispatcher' => function($sm) {
            $config = $sm->get('Config');
            $dispatcher = $sm->get('KJSencha\Direct\Remoting\Dispatcher');
            $api = $sm->get('kjsencha.api.module');
            $dispatcher->setApi($api);

            return $dispatcher;
        }
    )
);