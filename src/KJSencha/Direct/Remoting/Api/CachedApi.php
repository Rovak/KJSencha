<?php

namespace KJSencha\Direct\Remoting\Api;

use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Direct\Remoting\Api\Object\Method;
use KJSencha\Frontend\Direct\RemotingProvider;

/**
 * Holds a array version of the api, used as cache
 */
class CachedApi implements ApiInterface
{

    /**
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function toApiArray()
    {
        $api = $this->config;
        $actions = array();

        foreach ($this->config['actions'] as $name => $action) {
            $methods = array();
            foreach ($action['methods'] as $method) {
                $methods[] = array(
                    'name'      => $method['name'],
                    'len'       => $method['len'],
                    'module'    => $method['module'],
                );
            }
            $actions[$name] = $methods;
        }

        $api['actions'] = $actions;

        return $api;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->config;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {
        return $this->config['url'];
    }

    /**
     * {@inheritDoc}
     */
    public function setUrl($url)
    {
        $this->config['url'] = $url;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->config['name'] = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->config['name'];
    }

    /**
     * Set the namespace that will be used
     *
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->config['namespace'] = $namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function getAction($name)
    {
        $actionCfg = $this->config['actions'][$name];
        $action = new Action($actionCfg['objectName']);
        $action->setName($name);

        foreach ($actionCfg['methods'] as $methodCfg) {
            $method = new Method($methodCfg['objectName']);
            $method->setNumberOfParameters($methodCfg['len']);
            $action->addMethod($method);
        }

        return $action;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAction($action)
    {
        return isset($this->config['actions'][$action]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildRemotingProvider()
    {
        return new RemotingProvider($this->toApiArray());
    }
}
