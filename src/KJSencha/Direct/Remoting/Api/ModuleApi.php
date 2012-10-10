<?php

namespace KJSencha\Direct\Remoting\Api;

use InvalidArgumentException;
use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Frontend\Direct\RemotingProvider;
use Serializable;

/**
 * A simple container which holds API's from multiple modules
 */
class ModuleApi implements Serializable
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

    public function toArray()
    {
        $array = array(
            'type' => $this->getType(),
            'url' => $this->getUrl(),
            'actions' => array(),
        );

        foreach ($this->getActions() as $actionName => $action) {
            $array['actions'][$action->getName()] = $action->toArray();
        }

        return $array;
    }

    public function fromArray(array $apiArray)
    {
        if (isset($apiArray['type'])) {
            $this->setType($apiArray['type']);
        }

        if (isset($apiArray['url'])) {
            $this->setType($apiArray['url']);
        }

        if (isset($apiArray['actions']) && is_array($apiArray['actions'])) {
            foreach ($apiArray['actions'] as $name => $actionArray) {
                $action = new Action($name);
                $action->fromArray($actionArray);
                $this->addAction($name, $action);
            }
        }
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
