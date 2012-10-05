<?php

namespace KJSencha\Direct\Remoting\Api\Object;

/**
 * Action / Class which hold methods that can be run from Ext.Direct
 */
class Action extends AbstractObject
{

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
     * @param string $name
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
            $methods[$method->getName()] = array(
                'len' => $method->getNumberOfParameters(),
            );
        }

        return array(
            $this->getName() => $methods,
        );
    }

}
