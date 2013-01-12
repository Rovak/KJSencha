<?php

namespace KJSencha\Service;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class ComponentManagerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \KJSencha\Service\ComponentManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceConfig = $this->createConfig($serviceLocator);
        $componentManager = new ComponentManager($serviceConfig);
        $componentManager->addPeeringServiceManager($serviceLocator);
        return $componentManager;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ServiceManagerConfig
     */
    public function createConfig(ServiceLocatorInterface $serviceLocator)
    {
        $config = array();
        $moduleManager = $serviceLocator->get('ModuleManager');

        foreach ($moduleManager->getLoadedModules() as $module) {
            if (!is_callable(array($module, 'getComponentConfig'))
            ) {
                continue;
            }

            $config = ArrayUtils::merge($config, $module->getComponentConfig());
        }

        return new ServiceManagerConfig($config);
    }
}
