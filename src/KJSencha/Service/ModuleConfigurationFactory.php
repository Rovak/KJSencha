<?php

namespace KJSencha\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use KJSencha\Options;

class ModuleConfigurationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');

        $result = array();

        if ( ! isset($config['kjsencha'])) {
            throw new \Exception('No ExtJS configuration found.');
        }

        if (isset($config['kjsencha']['rest'])) {
            $result['rest'] = new Options\Rest($config['kjsencha']['rest']);
        }

        if (isset($config['kjsencha']['direct'])) {
            $result['direct'] = new Options\Direct($config['kjsencha']['direct']);
        }
        if (isset($config['kjsencha']['store'])) {
            $result['store'] = new Options\Store($config['kjsencha']['store']);
        }

        return $result;
    }
}
