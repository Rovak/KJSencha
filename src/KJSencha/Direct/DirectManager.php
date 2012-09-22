<?php

namespace KJSencha\Direct;

use Zend\ServiceManager\AbstractPluginManager;

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