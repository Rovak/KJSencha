<?php

namespace KJSencha\Direct\Polling;

/**
 * A polling task which can be run by the Task Runner
 */
class Task
{
    protected $class;
    protected $method;
    protected $interval = 0;

    /**
     * Retrieve classname
     * 
     * @return string
     */
    public function getClassname()
    {
        return $this->class;
    }

    /**
     * Set classname
     * 
     * @param string $class
     */
    public function setClassname($class)
    {
        $this->class = $class;
    }
    
    /**
     * Retrieve methodname
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Set methodname
     * 
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Retrieve interval
     * 
     * @return type
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set the interval in seconds
     * 
     * @param integer $interval Interval in seconds
     */
    public function setInterval($interval)
    {
        $this->interval = (int) $interval;
    }
    
    /**
     * Add interval
     * 
     * @param integer $interval
     */
    public function addInterval($interval)
    {
        $this->interval+= (int) $interval;
    }
}
