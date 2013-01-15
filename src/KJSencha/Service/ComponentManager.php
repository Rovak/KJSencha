<?php

namespace KJSencha\Service;

use Exception;
use KJSencha\Frontend\Base;
use UnexpectedValueException;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Component Manager
 */
class ComponentManager extends AbstractPluginManager
{
    /**
     * Constructor
     *
     * @param  null|ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        $this->addInitializer(array($this, 'injectDependencies'), false);
    }

    /**
     * Inject required dependencies into the component.
     *
     * @param  Base $component
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function injectDependencies($component, ServiceLocatorInterface $serviceLocator)
    {
        if ($component instanceof EventManagerAwareInterface) {
            $component->setEventManager($serviceLocator->get('EventManager'));
        }
    }

    /**
     * Validate the plugin
     *
     * Ensure we have a component.
     *
     * @param  mixed $plugin
     * @return true
     * @throws Exception
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Base) {
            return;
        }

        throw new UnexpectedValueException("Trying to retrieve an invalid component");
    }
}
