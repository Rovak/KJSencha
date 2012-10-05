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

use KJSencha\Frontend\Base;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Mvc\Exception;
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
        // Pushing to bottom of stack to ensure this is done last
        $this->addInitializer(array($this, 'injectDependencies'), false);
    }

    /**
     * Inject required dependencies into the controller.
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

        throw new Exception('Invalid component!');
    }
}
