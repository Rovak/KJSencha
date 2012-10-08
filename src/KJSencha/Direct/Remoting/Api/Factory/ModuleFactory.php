<?php

namespace KJSencha\Direct\Remoting\Api\Factory;

use KJSencha\Direct\Remoting\Api\Api;
use KJSencha\Direct\Remoting\Api\ModuleApi;
use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Direct\Remoting\Api\Object\Method;
use ArrayObject;
use InvalidArgumentException;

use Zend\Code\Annotation\AnnotationManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Code\Scanner\DirectoryScanner;
use Zend\Code\Scanner\FileScanner;
use Zend\Code\Scanner\MethodScanner;
use Zend\Code\Reflection\ClassReflection;
use Zend\Stdlib\ArrayUtils;

/**
 * Module Factory
 */
class ModuleFactory
{
    /**
     * @var AnnotationManager
     */
    protected $annotationManager;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function __construct(AnnotationManager $annotationManager, ServiceManager $serviceManager)
    {
        $this->annotationManager = $annotationManager;
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param array $api
     * @return ModuleApi
     */
    public function buildApi(array $apiConfig)
    {
        $modules = array();

        $apis = array();

        // legacy code, probably to be removed
        if (isset($apiConfig['modules']) && is_array($apiConfig['modules'])) {
            $apis = ArrayUtils::merge($apis, $this->buildDirectoryApi($apiConfig['modules']));
        }

        if (isset($apiConfig['services']) && is_array($apiConfig['services'])) {
            $apis = ArrayUtils::merge($apis, $this->buildServiceApi($apiConfig['services']));
        }

        var_dump($apis);
        exit(1);

        foreach ($modules as $name => $module) {
            $api = new Api();
            $api->setName($name);
            $api->setNamespace($name);

            /* @var $action \KJSencha\Direct\Remoting\Api\Object\Action */
            foreach ($module['actions'] as $action) {
                // Make action name relative to that of the module namespace
                $action->setName(substr($action->getName(), strlen($module['namespace']) + 1));
                $api->addAction($action);
            }

            $apis[$name] = $api;
        }

        return $apis;
    }

    /**
     * @deprecated this logic is deprecated and uses per-directory scanning. Instead, please
     *             map your defined service names in the 'services' config
     * @param array $modules
     * @return array
     * @throws InvalidArgumentException
     */
    protected function buildDirectoryApi(array $modules)
    {
        $apis = array();

        foreach ($modules as $module) {
            if (!isset($module['directory']) || !is_dir($module['directory'])) {
                throw new InvalidArgumentException('Invalid directory given: "' . $module['directory'] . '"');
            }

            $directoryScanner = new DirectoryScanner($module['directory']);

            /* @var $class \Zend\Code\Scanner\DerivedClassScanner */
            foreach ($directoryScanner->getClasses(true) as $class) {
                $serviceName = $class->getName();

                if (!$this->serviceManager->has($serviceName)) {
                    $this->serviceManager->setInvokableClass($serviceName, $serviceName);
                }

                // invoking to check if nothing went wrong - this avoids setting invalid services
                $service = $this->serviceManager->get($serviceName);
                $apis[$serviceName] = $this->buildAction(get_class($service));
            }
        }

        return $apis;
    }

    /**
     * @param array $modules
     * @return array
     * @throws InvalidArgumentException
     */
    protected function buildServiceApi(array $services)
    {
        $apis = array();

        foreach ($services as $name => $serviceName) {
            $service = $this->serviceManager->get($serviceName);
            $apis[$name] = $this->buildAction(get_class($service));
        }

        return $apis;
    }

    protected function buildAction($className)
    {
        $classReflection = new ClassReflection($className);
        $scanner = new FileScanner($classReflection->getFileName(), $this->annotationManager);
        $classScanner = $scanner->getClass($classReflection->getName());
        $action = new Action($classScanner->getName());

        foreach ($classScanner->getMethods() as $classMethod) {
            if ($classMethod->isPublic()) {
                $action->addMethod($this->buildMethod($classMethod));
            }
        }

        return $action;
    }

    protected function buildMethod(MethodScanner $classMethod)
    {
        $method = new Method($classMethod->getName());
        $method->setNumberOfParameters($classMethod->getNumberOfParameters());

        // Loop through annotations
        if ($annotations = $classMethod->getAnnotations($this->annotationManager)) {
            foreach ($annotations as $annotation) {
                if (method_exists($annotation, 'decorateObject')) {
                    $annotation->decorateObject($method);
                }
            }
        }

        return $method;
    }
}
