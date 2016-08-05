<?php

namespace KJSencha\Direct;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Manages the creation of Direct classes
 */
class DirectManager extends AbstractPluginManager
{
    /**
     * DirectManager constructor.
     * @param ServiceLocatorInterface $sl
     */
    public function __construct(ServiceLocatorInterface $sl)
    {
        parent::__construct();
        $this->setServiceLocator($sl);
    }

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
    }
}
