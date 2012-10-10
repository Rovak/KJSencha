<?php

namespace KJSencha\Service;

use KJSencha\Direct\Remoting\Api\CachedApi;
use KJSencha\Direct\Remoting\Api\ModuleApi;
use ArrayObject;

use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleApiFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ArrayObject
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
            $actions = $this->buildFromArray($cache->getItem('module_api'));
        } else {
            /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Factory\ModuleFactory */
            $apiFactory = $serviceLocator->get('kjsencha.modulefactory');
            $actions = $apiFactory->buildApi($config['kjsencha']['direct']);
            $this->saveToCache($actions, $cache);
        }

        $moduleApi = new ModuleApi($router->assemble(
            array('action'  => 'rpc'),
            array('name'    => 'kjsencha-direct')
        ));

        /* @var $actions \KJSencha\Direct\Remoting\Api\Object\Action[] */
        foreach ($actions as $name => $action) {
            $moduleApi->addAction($name, $action);
        }

        return $moduleApi;
    }

    /**
     * @param array $fetched
     * @return ArrayObject
     */
    protected function buildFromArray(array $fetched)
    {
        throw new \BadMethodCallException('Not yet supported - caching to be refactored!');
        $api = new ModuleApi();

        foreach ($fetched as $name => $cachedModule) {
            $api->addModule($name, new CachedApi($cachedModule['config']));
        }

        return $api;
    }

    /**
     * @param \Traversable[] $actions
     * @param StorageInterface $cache
     */
    protected function saveToCache($actions, StorageInterface $cache)
    {
        return;
        // @todo adapt to new changes
        $toStore = array();

        /* @var $action */
        foreach ($actions as $name => $api) {
            $toStore[$name] = array(
                'config' => $api->toArray(),
            );
        }

        $cache->setItem('module_api', $toStore);
    }
}
