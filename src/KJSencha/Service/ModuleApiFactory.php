<?php

namespace KJSencha\Service;

use KJSencha\Direct\Remoting\Api\CachedApi;
use KJSencha\Direct\Remoting\Api\ModuleApi;
use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleApiFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ModuleApi
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $config array */
        $config = $serviceLocator->get('Config');
        /* @var $cache StorageInterface */
        $cache = $serviceLocator->get('kjsencha.cache');
        /* @var $router \Zend\Mvc\Router\Http\RouteInterface */
        $router = $serviceLocator->get('HttpRouter');

        if ($cache->hasItem('module_api')) {
            $api = $this->buildFromArray($cache->getItem('module_api'));
        } else {
            /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Factory\ModuleFactory */
            $apiFactory = $serviceLocator->get('kjsencha.modulefactory');
            $api = $apiFactory->buildApi($config['kjsencha']['direct']);
            $this->saveToCache($api, $cache);
        }

        // Setup the correct url from where to request data
        $api->setUrl($router->assemble(
            array('action'  => 'rpc'), 
            array('name'    => 'kjsencha-direct')
        ));

        return $api;
    }

    /**
     * @param array $fetched
     * @return ModuleApi
     */
    protected function buildFromArray(array $fetched)
    {
        $api = new ModuleApi();

        foreach ($fetched as $name => $cachedModule) {
            $api->addModule($name, new CachedApi($cachedModule['config']));
        }

        return $api;
    }

    /**
     * @param ModuleApi $moduleApi
     * @param StorageInterface $cache
     */
    protected function saveToCache(ModuleApi $moduleApi, StorageInterface $cache)
    {
        $toStore = array();

        foreach ($moduleApi->getModules() as $name => $api) {
            $toStore[$name] = array(
                'config' => $api->toArray(),
            );
        }

        $cache->setItem('module_api', $toStore);
    }
}
