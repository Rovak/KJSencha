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
        $config = $serviceLocator->get('Config');
        /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Factory\ModuleFactory */
        $apiFactory = $serviceLocator->get('kjsencha.modulefactory');

        if (false !== $config['kjsencha']['direct']['cache']) {
            /* @var $cache StorageInterface */
            $cache = $serviceLocator->get('kjsencha.cache');

            if ($cache->hasItem('module_api')) {
                $api = $this->buildFromArray($cache->getItem('module_api'));
            } else {
                $api = $apiFactory->buildApi($config['kjsencha']['direct']);
                $this->saveToCache($api, $cache);
            }
        } else {
            $api = $apiFactory->buildApi($config['kjsencha']['direct']);
        }

        // Setup the correct url from where to request data
        $router = $serviceLocator->get('Router');
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

        $cache->setItem('module_api', $cache);
    }
}
