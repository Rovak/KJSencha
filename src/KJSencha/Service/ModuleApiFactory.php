<?php

namespace KJSencha\Service;

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
        /* @var $moduleApi \KJSencha\Direct\Remoting\Api\ModuleApi */
        $moduleApi = $cache->getItem($config['kjsencha']['cache_key'], $success);

        //var_dump('cached:');
        //var_dump($moduleApi);

        if (!$success) {
            /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Factory\ModuleFactory */
            $apiFactory = $serviceLocator->get('kjsencha.modulefactory');
            $moduleApi = $apiFactory->buildApi($config['kjsencha']['direct']);
            $moduleApi->setUrl($router->assemble(
                array('action'  => 'rpc'),
                array('name'    => 'kjsencha-direct')
            ));
            $cache->setItem($config['kjsencha']['cache_key'], $moduleApi);
            //var_dump('writing cache:');
            //var_dump($moduleApi);
        }
        //die();

        return $moduleApi;
    }
}
