<?php

namespace KJSencha\Direct\Remoting\Api\Object;

use InvalidArgumentException;

/**
 * Action / Class which hold methods that can be run from Ext.Direct
 */
class Action extends AbstractObject
{
    /**
     * @var Method[]
     */
    protected $methods = array();

    /**
     * Add a method
     *
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $this->methods[$method->getObjectName()] = $method;
    }

    /**
     * Does this action have the given method
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasMethod($name)
    {
        return isset($this->methods[$name]);
    }

    /**
     * Get the methods
     *
     * @return Method[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param  string $name
     * @return Method
     */
    public function getMethod($name)
    {
        return $this->methods[$name];
    }

    /**
     * @inheritdoc
     */
    public function toApiArray()
    {
        $methods = array();

        foreach ($this->getMethods() as $method) {
            $methods[$method->getName()] = $method->toApiArray();
        }

        return array(
            $this->getName() => $methods,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        $data = array(
            'methods'    => $this->getMethods(),
            'parentData' => parent::serialize(),
        );

        return serialize($data);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (!is_array($data) || !isset($data['parentData'])) {
            throw new InvalidArgumentException('Incorrect unserialized data');
        }

        if (isset($data['methods'])) {
            $this->methods = $data['methods'];
        }

        parent::unserialize($data['parentData']);
    }
}
