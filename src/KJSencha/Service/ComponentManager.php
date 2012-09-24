<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mvc
 */

namespace KJSencha\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\Mvc\Exception;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ExtJS
 */
class ComponentManager extends AbstractPluginManager
{
    /**
     * Constructor
     *
     * After invoking parent constructor, add an initializer to inject the
     * service manager, event manager, and plugin manager
     *
     * @param  null|ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        // Pushing to bottom of stack to ensure this is done last
        $this->addInitializer(array($this, 'injectDependencies'), false);
    }

    /**
     * Inject required dependencies into the controller.
     *
     * @param  DispatchableInterface $controller
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function injectDependencies($controller, ServiceLocatorInterface $serviceLocator)
    {
        if ($controller instanceof EventManagerAwareInterface) {
            $controller->setEventManager($serviceLocator->get('EventManager'));
        }
    }

    /**
     * Validate the plugin
     *
     * Ensure we have a dispatchable.
     *
     * @param  mixed $plugin
     * @return true
     * @throws Exception\InvalidControllerException
     */
    public function validatePlugin($plugin)
    {
        return;
    }
}
