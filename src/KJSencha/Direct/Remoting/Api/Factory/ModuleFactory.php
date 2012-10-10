<?php

namespace KJSencha\Direct\Remoting\Api\Factory;

use KJSencha\Direct\Remoting\Api\ModuleApi;
use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Direct\Remoting\Api\Object\Method;
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
     * @param array $apiConfig
     * @return ModuleApi
     */
    public function buildApi(array $apiConfig)
    {
        $actions = array();

        // legacy code, probably to be removed
        if (isset($apiConfig['modules']) && is_array($apiConfig['modules'])) {
            $actions = ArrayUtils::merge($actions, $this->buildDirectoryApi($apiConfig['modules']));
        }

        if (isset($apiConfig['services']) && is_array($apiConfig['services'])) {
            $actions = ArrayUtils::merge($actions, $this->buildServiceApi($apiConfig['services']));
        }

        $moduleApi = new ModuleApi();

        /* @var $actions \KJSencha\Direct\Remoting\Api\Object\Action[] */
        foreach ($actions as $name => $action) {
            $moduleApi->addAction($name, $action);
        }

        return $moduleApi;
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
        $api = array();

        foreach ($modules as $moduleName => $module) {
            if (!isset($module['directory']) || !is_dir($module['directory'])) {
                throw new InvalidArgumentException('Invalid directory given: "' . $module['directory'] . '"');
            }

            if (!isset($module['namespace']) || !is_string($module['namespace'])) {
                throw new InvalidArgumentException('Invalid namespace provided for module "' . $moduleName. '"');
            }

            $jsNamespace = rtrim(str_replace('\\', '.', $module['namespace']), '.') . '.';
            $directoryScanner = new DirectoryScanner($module['directory']);

            /* @var $class \Zend\Code\Scanner\DerivedClassScanner */
            foreach ($directoryScanner->getClasses(true) as $class) {
                // now building the service name as exposed client-side
                $className = $class->getName();
                $jsClassName = str_replace('\\', '.', substr($className, strlen($module['namespace']) + 1));
                $jsClassNames = explode('.', $jsClassName);
                $chunks = count($jsClassNames);

                // lcfirst all chunks except the last one
                for ($i = 1; $i < $chunks; $i += 1) {
                    $jsClassNames[$i - 1] = lcfirst($jsClassNames[$i - 1]);
                }

                $serviceName = $jsNamespace . implode('.', $jsClassNames);

                if (!$this->serviceManager->has($serviceName)) {
                    $this->serviceManager->setInvokableClass($serviceName, $className);
                }

                // invoking to check if nothing went wrong - this avoids setting invalid services
                $service = $this->serviceManager->get($serviceName);
                $action = $this->buildAction(get_class($service));
                $action->setName($serviceName);
                $action->setObjectName($className);
                $api[$serviceName] = $action;
            }
        }

        return $api;
    }

    /**
     * @param array $services
     * @return Action[]
     * @throws InvalidArgumentException
     */
    protected function buildServiceApi(array $services)
    {
        $api = array();

        foreach ($services as $name => $serviceName) {
            // @todo validate service name?
            $service = $this->serviceManager->get($serviceName);
            $action = $this->buildAction(get_class($service));
            $action->setName($name);
            $action->setObjectName($serviceName);
            $api[$name] = $action;
        }

        return $api;
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
                // @todo annotations should implement some kind of interface?
                if (method_exists($annotation, 'decorateObject')) {
                    $annotation->decorateObject($method);
                }
            }
        }

        return $method;
    }
}
