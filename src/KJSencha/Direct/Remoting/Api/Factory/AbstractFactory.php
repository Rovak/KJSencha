<?php

namespace KJSencha\Direct\Remoting\Api\Factory;

use InvalidArgumentException;

use KJSencha\Direct\Remoting\Api\Api;
use KJSencha\Direct\Remoting\Api\Object;
use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Direct\Remoting\Api\Object\Method;

use Zend\Code\Scanner\DirectoryScanner;
use Zend\Code\Scanner\DerivedClassScanner;
use Zend\Code\Annotation\AnnotationManager;

/**
 * Api Factory
 */
abstract class AbstractFactory
{
    /**
     * Retrieves the AnnotationManager used to discover features of built API
     *
     * @return AnnotationManager
     */
    abstract protected function getAnnotationManager();

    /**
     * Build API from the classes which are contained in the given directory
     *
     * @param  string $path Path to a valid directory
     * @return Api    The API object
     */
    public function buildFromDirectory($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException('Invalid directory given: "' . $path . '"');
        }

        $objects = array();
        $directoryScanner = new DirectoryScanner($path);

        foreach ($directoryScanner->getClasses(true) as $class) {
            $objects[] = $this->buildObjectFromClass($class);
        }

        return $objects;
    }

    /**
     * @param DerivedClassScanner $class
     * @return Action
     */
    public function buildObjectFromClass(DerivedClassScanner $class)
    {
        $action = new Action($class->getName());

        foreach ($class->getMethods(true) as $classMethod) {

            // Only public callable methods are allowed
            if (false === $classMethod->isPublic()) {
                continue;
            }

            // Create method
            $method = new Method($classMethod->getName());
            $method->setNumberOfParameters($classMethod->getNumberOfParameters());
            
            // Loop through annotations
            if ($annotations = $classMethod->getAnnotations($this->getAnnotationManager())) {
                foreach ($annotations as $annotation) {
                    if (method_exists($annotation, 'decorateObject')) {
                        $annotation->decorateObject($method);
                    }
                }
            }

            $action->addMethod($method);
        }

        return $action;
    }
}
