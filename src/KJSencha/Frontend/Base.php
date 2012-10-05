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
     * @param string $name
     * @param array  $attributes
     */
    public function __construct($name = NULL, array $attributes = NULL)
    {
        if (is_array($name)) {
            $attributes = $name;
        }

        if (is_array($attributes)) {
            $this->attributes = ArrayUtils::merge($this->attributes, $attributes);
        }
    }

    /**
     * Factory system
     *
     * @param mixed
     */
    public static function factory($attributes)
    {
        return new static($attributes);
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setProperty($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Set a raw expression
     *
     * @param string $key
     * @param string $value
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
            'enableJsonExprFinder' => TRUE,
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
     * @return [type] [description]
     */
    public function render()
    {
        return $this->toJson();
    }

    /**
     * @param  string  $key
     * @return boolean
     */
    public function offsetExists($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }

        return null;
    }

    /**
     * @param  type       $key
     * @param  type       $value
     * @return type
     * @throws \Exception
     */
    public function offsetSet($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Unset
     * @param  [type] $key [description]
     * @return [type] [description]
     */
    public function offsetUnset($key)
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
        }
    }
}
