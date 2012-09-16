<?php

namespace KJSencha\Direct\Remoting\Api;

use Exception;
use KJSencha\Direct\Remoting\Api\Object\Action;
use KJSencha\Frontend\Direct\RemotingProvider;

/**
 * Ext.Direct API
 */
class Api implements ApiInterface
{

    /**
     * @var array
     */
    protected $actions = array();

    /**
     * Javascript variable name which will hold the Ext.Direct functions
     *
     * @var string
     */
    protected $namespace = 'Direct';

    /**
     * ExtJS Widget which will be used
     *
     * @var string
     */
    protected $type = 'kjsenchamoduleremoting';

    /**
     * URL where the requests will be send
     *
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * Set options
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'actions':
                    $this->actions = $value;
                    break;
                case 'namespace':
                    $this->setNamespace($value);
                    break;
                case 'type':
                    $this->setType($value);
                    break;
                case 'url':
                    $this->setUrl($value);
                    break;
            }
        }
    }

    /**
     * Add a action to the API
     *
     * @param Action $object Direct object
     */
    public function addAction(Action $action)
    {
        $this->actions[$action->getName()] = $action;
    }

    /**
     * Return API actions
     *
     * @return Action[] Actions
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Check if this API holds the give action
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasAction($name)
    {
        return isset($this->actions[$name]);
    }

    /**
     * Retrieve action
     *
     * @param  string  $name
     * @return boolean
     */
    public function getAction($name)
    {
        if (!$this->hasAction($name)) {
            throw new Exception('Action does not exist');
        }

        return $this->actions[$name];
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set the The name which will be used javascript side to hold the
     * Ext.Direct methods
     *
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
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
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the URL to where the requests will be send
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $defaults = array(
            'type'      => $this->getType(),
            'url'       => $this->getUrl(),
            'name'      => $this->getNamespace(),
            'namespace' => $this->getNamespace(),
            'actions'   => array(),
        );

        foreach ($this->getActions() as $action) {

            $methods = array();

            foreach ($action->getMethods() as $method) {
                $methods[] = array_merge($method->toArray(), array(
                    'module' => $this->getNamespace(),
                        ));
            }

            $actionArray = array_merge($action->toArray(), array(
                'methods' => $methods,
            ));

            $defaults['actions'][$action->getName()] = $actionArray;
        }

        return $defaults;
    }

    /**
     * @inheritdoc
     */
    public function toApiArray()
    {
        $defaults = array(
            'type' => $this->getType(),
            'url' => $this->getUrl(),
            'namespace' => $this->getNamespace(),
            'actions' => array(),
        );

        foreach ($this->getActions() as $action) {

            $methods = array();

            foreach ($action->getMethods() as $method) {
                $methods[] = array_merge($method->toApiArray(), array(
                    'module' => $this->getName(),
                ));
            }

            $defaults['actions'][$action->getName()] = $methods;
        }

        return $defaults;
    }

    /**
     * @return RemotingProvider
     */
    public function buildRemotingProvider()
    {
        return new RemotingProvider($this->toApiArray());
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
