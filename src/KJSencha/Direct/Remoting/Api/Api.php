<?php

namespace KJSencha\Direct\Remoting\Api;

use InvalidArgumentException;
use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Frontend\Direct\RemotingProvider;
use Serializable;

/**
 * A simple container which holds API's from multiple modules
 */
class Api implements Serializable, ApiInterface
{
    /**
     * @var string
     */
    protected $url = '';

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
     * {@inheritDoc}
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function hasAction($name)
    {
        return isset($this->actions[$name]);
    }

    /**
     * {@inheritDoc}
     *
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

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            'type'    => $this->getType(),
            'url'     => $this->getUrl(),
            'actions' => $this->getActions(),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Incorrect unserialized data');
        }

        if (isset($data['type'])) {
            $this->setType($data['type']);
        }

        if (isset($data['url'])) {
            $this->setUrl($data['url']);
        }

        foreach ($data['actions'] as $actionName => $action) {
            $this->addAction($actionName, $action);
        }
    }
}
