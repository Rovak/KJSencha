<?php

namespace KJSencha\Direct\Remoting\Api\Factory;

use InvalidArgumentException;
use KJSencha\Annotation;
use KJSencha\Direct\Remoting\Api\Api;
use KJSencha\Direct\Remoting\Api\Object;
use Zend\Code;
use Zend\Code\Annotation\AnnotationManager;

/**
 * Api Factory
 */
abstract class AbstractFactory
{
    protected $annotationManager;

    /**
     * Annotation manager
     *
     * @return AnnotationManager [description]
     */
    public function getAnnotationManager()
    {
        if (null == $this->annotationManager) {
            $this->annotationManager = new AnnotationManager(array(
                new Annotation\Remotable,
                new Annotation\Formhandler,
            ));
        }

        return $this->annotationManager;
    }

    /**
     * @param AnnotationManager $annotationManager
     */
    public function setAnnotationManager($annotationManager)
    {
        $this->annotationManager = $annotationManager;
    }

    /**
     * Build API from the classes which are contained in the given directory
     *
     * @param  string $path Path to a valid directory
     * @return Api    The API object
     */
    public function buildFromDirectory($path)
    {
        if ( ! is_dir($path)) {
            throw new InvalidArgumentException(
                'Invalid directory given: ' . $path
            );
        }

        $objects = array();

        $directoryScanner = new Code\Scanner\DirectoryScanner($path);

        /* @var $class DerivedClassScanner */
        foreach ($directoryScanner->getClasses(TRUE) as $class) {
            $objects[] = $this->buildObjectFromClass($class);
        }

        return $objects;
    }

    /**
     * Convert class reflection to object
     *
     * @param  [type] $class [description]
     * @return [type] [description]
     */
    public function buildObjectFromClass($class)
    {
        $object = new Object\Action($class->getName());

        foreach ($class->getMethods(TRUE) as $classMethod) {

            // Only public callable methods are allowed
            if (false === $classMethod->isPublic()) {
                continue;
            }

            // Create method
            $method = new Object\Method($classMethod->getName());
            $method->setNumberOfParameters($classMethod->getNumberOfParameters());

            // Loop through annotations
            if ($annotations = $classMethod->getAnnotations($this->getAnnotationManager())) {
                foreach ($annotations as $annotation) {
                    if (method_exists($annotation, 'decorateObject')) {
                        $annotation->decorateObject($method);
                    }
                }
            }

            $object->addMethod($method);
        }

        return $object;
    }
}
