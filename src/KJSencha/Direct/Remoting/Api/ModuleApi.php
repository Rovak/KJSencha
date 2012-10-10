<?php

namespace KJSencha\Direct\Remoting\Api;

use InvalidArgumentException;
use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Frontend\Direct\RemotingProvider;

/**
 * A simple container which holds API's from multiple modules
 */
class ModuleApi
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var Action[]
     */
    protected $actions = array();

    /**
     * ExtJS Widget which will be used
     *
     * @var string
     */
    protected $type = 'kjsenchamoduleremoting';

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->setUrl($url);
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $name
     * @param Action $action
     */
    public function addAction($name, Action $action)
    {
        $this->actions[(string) $name] = $action;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAction($name)
    {
        return isset($this->actions[$name]);
    }

    /**
     * @param string $name
     * @return Action
     * @throws InvalidArgumentException
     */
    public function getAction($name)
    {
        if (!array_key_exists($name, $this->actions)) {
            throw new InvalidArgumentException('Requested action "' . $name . '" does not exist');
        }

        return $this->actions[$name];
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Ext.Widget name
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the Ext.Widget name
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = (string) $type;
    }

    /**
     * @return array
     */
    public function toApiArray()
    {
        $defaults = array(
            'type' => $this->getType(),
            'url' => $this->getUrl(),
            'actions' => array(),
        );

        foreach ($this->getActions() as $action) {
            $methods = array();

            foreach ($action->getMethods() as $method) {
                $methods[] = $method->toApiArray();
            }

            $defaults['actions'][$action->getName()] = $methods;
        }

        return $defaults;
    }

    /**
     * {@inheritDoc}
     */
    public function buildRemotingProvider()
    {
        return new RemotingProvider($this->toApiArray());
    }
}
