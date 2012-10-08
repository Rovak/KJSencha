<?php

namespace KJSencha\Direct\Remoting\Api\Factory;

use KJSencha\Direct\Remoting\Api\Api;
use KJSencha\Direct\Remoting\Api\ModuleApi;

use Zend\Code\Annotation\AnnotationManager;
use Zend\ServiceManager\ServiceLocatorInterface;

use InvalidArgumentException;

/**
 * Module Factory
 */
class ModuleFactory extends AbstractFactory
{
    /**
     * @var AnnotationManager
     */
    protected $annotationManager;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function __construct(AnnotationManager $annotationManager, ServiceLocatorInterface $serviceLocator)
    {
        $this->annotationManager = $annotationManager;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritDoc}
     */
    protected function getAnnotationManager()
    {
        return $this->annotationManager;
    }

    /**
     * @param array $api
     * @return ModuleApi
     */
    public function buildApi(array $apiConfig)
    {
        $modules = array();

        foreach ($apiConfig['modules'] as $name => $module) {
            $modules[$name] = array_merge($module, array(
                'actions' => $this->buildFromDirectory($module['directory']),
            ));
        }

        $moduleApi = new ModuleApi();

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

            $moduleApi->addModule($name, $api);
        }

        $api = new Api();
        $api->setName('');
        $api->setNamespace('');

        foreach ($apiConfig['services'] as $name => $serviceName) {
            $name = is_string($name) ? $name : $serviceName;

            if (!$this->serviceLocator->has($serviceName)) {
                throw new InvalidArgumentException(
                    'Service "' . $serviceName . '" was mapped in the API but could not be found'
                );
            }

            $service = $this->serviceLocator->get($serviceName);
            $class = new \Zend\Code\Reflection\ClassReflection(get_class($service));
            $scanner = new \Zend\Code\Scanner\FileScanner($class->getFileName(), $this->getAnnotationManager());

            $classScanner = $scanner->getClass($class->getName());
            $action = $this->buildObjectFromClass($classScanner);
            $action->setName($name);
            $api->addAction($action);
        }

        $moduleApi->addModule('', $api);

        return $moduleApi;
    }
}
