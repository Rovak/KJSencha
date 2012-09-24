<?php

namespace KJSencha\Service;

use KJSencha\Direct\Remoting\Api\CachedApi;
use KJSencha\Direct\Remoting\Api\ModuleApi;
use Zend\Cache\Storage\AdapterPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleApiFactory implements FactoryInterface
{
    protected $services;

    /**
     * @return AdapterPluginManager
     */
    public function getCache()
    {
        return $this->services->get('kjsencha.cache');
    }

    /**
     * Create a module api service, automaticly takes the cached version
     * if its available
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return ModuleApi
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;

        $config = $serviceLocator->get('Config');

        if (false === $config['kjsencha']['direct']['cache']) {
            $api = $this->buildApi();
        } else {
            $cache = $this->getCache();
            if ($cache->hasItem('module_api')) {
                $api = $this->buildFromArray($cache->getItem('module_api'));
            } else {
                $api = $this->buildApi();
                $this->saveToCache($api);
            }
        }

        // Setup the correct url from where to request data
        $router = $serviceLocator->get('Router');
        $api->setUrl($router->assemble(
            array('action'  => 'rpc'),
            array('name'    => 'kjsencha-direct'))
        );

        return $api;
    }

    /**
     * @param  array     $options
     * @return ModuleApi
     */
    public function buildFromArray(array $options)
    {
        $api = new ModuleApi;
        foreach ($options as $name => $cachedModule) {
            $api->addModule($name, new CachedApi($cachedModule['config']));
        }

        return $api;
    }

    /**
     * Save a ModuleApi to the cache
     *
     * @param ModuleApi $moduleApi
     */
    public function saveToCache(ModuleApi $moduleApi)
    {
        $cache = array();

        foreach ($moduleApi->getModules() as $name => $api) {
            $cache[$name] = array(
                'config' => $api->toArray(),
            );
        }

        $this->getCache()->setItem('module_api', $cache);
    }

    /**
     * Build module from scratch
     *
     * @return ModuleApi
     */
    public function buildApi()
    {
        $config = $this->services->get('Config');
        $apiFactory = $this->services->get('kjsencha.modulefactory');
        $api = $apiFactory->buildApi($config['kjsencha']['direct']);

        return $api;
    }
}
