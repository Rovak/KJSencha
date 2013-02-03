<?php

namespace KJSencha\Direct\Remoting\Api\Object;

/**
 * A method which can be run by Ext.Direct
 */
class Method extends AbstractObject
{
    /**
     * @var int
     */
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
     * @inheritdoc
     */
    public function toApiArray()
    {
        return array_merge(
            $this->getOptions(),
            array(
                'name'  => $this->getName(),
                'len'   => $this->getNumberOfParameters(),
            )
        );
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
     * Retrieve options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Retrieve a single option
     *
     * @param string $key
     * @return string
     */
    public function getOption($key)
    {
        if (!isset($this->options[$key])) {
            return null;
        }

        return $this->options[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            'numberOfParameters'    => $this->getNumberOfParameters(),
            'options'               => $this->getOptions(),
            'parentData'            => parent::serialize(),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (!is_array($data) || !isset($data['parentData'])) {
            throw new \InvalidArgumentException('Incorrect unserialized data');
        }

        if (isset($data['numberOfParameters'])) {
            $this->setNumberOfParameters($data['numberOfParameters']);
        }

        if (isset($data['options'])) {
            $this->options = $data['options'];
        }

        parent::unserialize($data['parentData']);
    }
}
