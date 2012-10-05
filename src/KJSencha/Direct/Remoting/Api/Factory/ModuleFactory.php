<?php

namespace KJSencha\Direct\Remoting\Api\Factory;

use KJSencha\Direct\Remoting\Api\Api;
use KJSencha\Direct\Remoting\Api\ModuleApi;

use Zend\Code\Annotation\AnnotationManager;

/**
 * Module Factory
 */
class ModuleFactory extends AbstractFactory
{
    /**
     * @var AnnotationManager
     */
    protected $annotationManager;

    public function __construct(AnnotationManager $annotationManager)
    {
        $this->annotationManager = $annotationManager;
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
    public function buildApi(array $api)
    {
        $modules = array();
        if (isset($api['modules'])) {
            foreach ($api['modules'] as $name => $module) {
                $modules[$name] = array_merge($module, array(
                    'actions' => $this->buildFromDirectory($module['directory']),
                ));
            }
        }

        $moduleApi = new ModuleApi;

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

        return $moduleApi;
    }
}
