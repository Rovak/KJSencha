<?php

namespace KJSencha\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;

/**
 * Creates Annotation Managers
 */
class AnnotationManagerFactory implements FactoryInterface
{
    protected $annotations = array(
        'KJSencha\Annotation\Remotable',
        'KJSencha\Annotation\Interval',
        'KJSencha\Annotation\Group'
    );
    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $annotationManager = new AnnotationManager;
        $doctrineParser = new DoctrineAnnotationParser;

        foreach ($this->annotations as $annotationClass) {
            $doctrineParser->registerAnnotation($annotationClass);
        }

        $annotationManager->attach($doctrineParser);
    }
}