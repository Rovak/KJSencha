<?php

namespace KJSencha\Direct\Remoting\Api\Factory;

use KJSencha\Direct\Remoting\Api\Api;
use KJSencha\Direct\Remoting\Api\ModuleApi;

/**
 * Module Factory
 */
class ModuleFactory extends AbstractFactory
{
    /**
     * New API
     *
     * @param  array  $api [description]
     * @return [type] [description]
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
            $api = new Api;
            $api->setName($name);
            $api->setNamespace($name);

            foreach ($module['actions'] as $action) {
                // Make action name relative to that of the module namespace
                $action->setName(substr($action->getName(), strlen($module['namespace']) + 1));

                $api->addAction($action);
            }
            $moduleApi->addModule($name, $api);
        }

        return $moduleApi;
    }

    /**
     * Filternaam
     *
     * @param  [type] $name [description]
     * @return [type] [description]
     */
    public function filterName($name)
    {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
    }
}
