<?php

namespace KJSencha\Direct;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\DispatchableInterface;


/**
 * Manages the creation of Direct classes
 */
class DirectManager extends AbstractPluginManager
{

    /**
     * Validate that we may create the called Direct class
     * 
     * @param string $plugin
     */
    public function validatePlugin($plugin)
    {
        return;
    }
}