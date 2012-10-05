<?php

namespace KJSencha\Frontend;

/**
 * Component object
 *
 * Simple object representation of a extjs object
 */
class Component extends Base
{
    /**
     * Classname of this component
     *
     * @var string
     */
    protected $className;

    /**
     * Extend
     *
     * @var string
     */
    protected $extend;

    /**
     * Create a Component class
     *
     * @param string $name
     * @param array  $attributes
     */
    public function __construct($name = NULL, array $attributes = NULL)
    {
        parent::__construct($name, $attributes);

        // Attempt to fetch classname
        if (is_string($name)) {
            $this->setClassname($name);
        } elseif (isset($this['className'])) {
            $this->setClassname($this['className']);
            unset($this['className']);
        }
    }

    /**
     * Set the classname
     *
     * @param  string                   $className
     * @return \ExtJs\Web\AbstractClass
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Retrieve the classname
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set the class which this class should extend
     *
     * @param string $extends
     */
    public function setExtend($extends)
    {
        $this->setProperty('extend', $extends);

        return $this;
    }

    /**
     * @return string
     */
    public function getExtend()
    {
        return $this->getProperty('extend');
    }

    /**
     * Create this class javascript side
     *
     * @return string Optional name of class that is created
     */
    public static function create(Component $obj = null)
    {
        if (null == $obj) {
            $obj = new static();
        }

        $output = sprintf(
            "Ext.create('%s', %s);",
            $obj->getClassName(),
            $obj->toJson()
        );

        return new Expr($output);
    }

    /**
     * Retrieve the code to define this PHP class
     *
     * @return
     */
    public static function define(Component $obj = null)
    {
        if (null == $obj ) {
            $obj = new static();
        }

        $obj = clone $obj;
        unset($obj['xtype']);

        $output = sprintf(
            "Ext.define('%s', %s);",
            $obj->getClassName(),
            $obj->toJson()
        );

        return new Expr($output);
    }
}
