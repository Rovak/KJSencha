<?php

namespace KJSenchaTestAsset\Direct;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Service Action which has access to the service layer
 */
class ServiceAction implements ServiceLocatorAwareInterface
{
    protected $sl;

    public function __construct()
    {

    }

    public function getServiceLocator()
    {
        return $this->sl;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
    }


    public function getServiceResult()
    {

        /* @var $echoService \KJSenchaTestAsset\Service\EchoService */
        $echoService = $this->getServiceLocator()->get('echo');

        return $echoService->ping();
    }
}