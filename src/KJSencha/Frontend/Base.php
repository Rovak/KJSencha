<?php

namespace KJSencha\Frontend;

use Zend\Stdlib\ArrayUtils;
use Zend\Json\Json;
use ArrayAccess;

/**
 * Base object
 *
 * Simple object representation of a extjs object
 */
class Base implements ArrayAccess
{
    protected $attributes = array();

    /**
     * Create a base class
     *
     * @param  string           $name
     * @param  array            $attributes
     * @throws \DomainException if provided attributes is not an array
     */
    public function __construct($name = null, array $attributes = null)
    {
        if (is_array($name)) {
            $attributes = $name;
        }

        if ( null !== $name && ! is_array($attributes)) {
            throw new \DomainException('Invalid input');
        }

        $this->attributes = ArrayUtils::merge($this->attributes, $attributes);
    }

    /**
     * Factory system
     *
     * @param mixed
     * @return self
     */
    public static function factory($attributes)
    {
        return new static($attributes);
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return self
     */
    public function setProperty($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Set a raw expression
     *
     * @param  string $key
     * @param  string $value
     * @return self
     */
    public function setExpr($key, $value)
    {
        if (! $value instanceof Expr) {
            $value = new Expr($value);
        }

        $this->setProperty($key, $value);

        return $this;
    }

    /**
     * Retrieve as array
     *
     * @return array Array
     */
    public function toArray()
    {
        // Recursive mapping, convert to class later
        $map = function($func, $arr) use (&$map) {
            $result = array();
            foreach ($arr as $k => $v) {
                $result[$k] = is_array($v) ? $map($func, $v) : $func($v);
            }

            return $result;
        };

        return $map(function($item){
            if ($item instanceof Base) {
                return $item->toArray();
            }

            return $item;
        }, $this->attributes);
    }

    /**
     * Convert object to JSON format
     *
     * @return string JSON format
     */
    public function toJson()
    {
        return Json::encode($this->toArray(), false, array(
            'enableJsonExprFinder' => true,
        ));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Render as ExtJS class
     * @return string
     */
    public function render()
    {
        return $this->toJson();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($key)
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
        }
    }
}
