<?php

namespace KJSencha\Direct\Remoting\Api\Object;

/**
 * A method which can be run by Ext.Direct
 */
class Method extends AbstractObject
{
    private $numberOfParameters = 0;
    
    /**
     * @var array
     */
    private $options = array();

    /**
     * The number of required parameters on this method
     *
     * @return int The number of parameters on this method
     */
    public function getNumberOfParameters()
    {
        return $this->numberOfParameters;
    }

    /**
     * Set number of parameters
     *
     * @param integer $numberOfParameters
     */
    public function setNumberOfParameters($numberOfParameters)
    {
        $this->numberOfParameters = (int) $numberOfParameters;
    }

    /**
     * @return type
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), $this->toApiArray());
    }

    /**
     * @inheritdoc
     */
    public function toApiArray()
    {
        return array_merge($this->getOptions(), array(
            'name'		=> $this->getName(),
            'len'		=> $this->getNumberOfParameters(),
        ));
    }
    
    /**
     * Set an option
     * 
     * @param string $name
     * @param string $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }
 
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
