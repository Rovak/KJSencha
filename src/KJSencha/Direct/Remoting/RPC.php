<?php

namespace KJSencha\Direct\Remoting;

use Zend\Stdlib\ArrayUtils;

/**
 * RPC Object
 *
 * Holds the information for the Direct requests
 */
class RPC
{
    /**
     * Arguments data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Unique transaction id
     *
     * @var integer
     */
    protected $id;

    /**
     * Action
     *
     * @var string
     */
    protected $action;

    /**
     * Methodname
     *
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $parameters = array();

    public static function factory(array $data)
    {
        $rpc = new self();
        $rpc->setId($data['tid']);
        $rpc->setAction($data['action']);
        $rpc->setMethod($data['method']);
        $rpc->setData($data['data'] ?: array());
        $rpc->setParameters($data);

        return $rpc;
    }

    /**
     * Data
     * @return array Request data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the data
     * @param array $data [description]
     */
    public function setData(array $data)
    {
        if (ArrayUtils::isHashTable($data)) {
            $data = array($data);
        }
        $this->data = $data;
    }

    /**
     * Unique procedure id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = (string) $action;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the method
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = (string) $method;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param  string  $name
     * @return boolean
     */
    public function hasParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->parameters[$name];
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }
}
