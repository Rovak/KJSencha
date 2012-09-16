<?php

namespace KJSencha\Common;

use Traversable;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Common service functions
 *
 * Taken from ZfcBase (https://github.com/ZF-Commons/ZfcBase)
 */
class AbstractService implements ServiceLocatorAwareInterface, EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * @var ServiceManagerInterface
     */
    protected $services;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    /**
     * @return ServiceManagerInterface ServiceManager
     */
    public function getServiceLocator()
    {
        return $this->services;
    }

    /**
     * Set the event manager instance
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(__CLASS__, get_class($this)));
        $this->events = $events;
        $this->attachDefaultListeners();

        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->events;
    }

    /**
     * Method merges return values of each listener's response into original $argv array and returns it.
     *
     * @param  string   $event
     * @param  array    $argv
     * @param  callback $callback
     * @return array
     */
    protected function triggerParamsMergeEvent($event, $argv = array(), $callback = null)
    {
        $eventRet = $this->triggerEvent($event, $argv, $callback);
        foreach ($eventRet as $event) {
            if (is_array($event) || $event instanceof Traversable) {
                $argv = array_merge_recursive($argv, $event);
            }
        }

        return $argv;
    }

    /**
     * @param  type $event
     * @param  type $argv
     * @param  type $callback
     * @return type
     */
    protected function triggerEvent($event, $argv = array(), $callback = null)
    {
        return $this->getEventManager()->trigger($event, $this, $argv, $callback);
    }

    protected function attachDefaultListeners()
    {
    }
}
