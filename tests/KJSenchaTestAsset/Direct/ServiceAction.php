<?php

namespace KJSenchaTestAsset\Direct;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Service Action which has access to the service layer
 */
class ServiceAction implements ServiceLocatorAwareInterface
{
    public function __construct()
    {

    }

    public function getServiceLocator()
    {

    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {

    }


    public function getServiceResult()
    {
        return 'test';
    }
}