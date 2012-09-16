<?php

namespace KJSencha\Direct\Remoting\Api;

use DomainException;
use KJSencha\Direct\Remoting\Api\Api;

/**
 * A simple container which holds API's from multiple modules
 */
class ModuleApi
{
    /**
     * Set the URL
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        foreach ($this->getModules() as $module) {
            $module->setUrl($url);
        }
    }

    /**
     * @var array
     */
    protected $modules = array();

    /**
     * Add a module API
     *
     * @param string $name
     * @param Api    $api
     */
    public function addModule($name, ApiInterface $api)
    {
        $this->modules[$name] = $api;
    }

    /**
     * @param  string  $name
     * @return boolean
     */
    public function hasModule($name)
    {
        return isset($this->modules[$name]);
    }

    /**
     * @return Api[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Retrieve a module
     *
     * @param  string          $name
     * @return Api
     * @throws DomainException
     */
    public function getModule($name)
    {
        if ( ! $this->hasModule($name)) {
            throw new \Exception('Module not found');
        }

        return $this->modules[$name];
    }
}
