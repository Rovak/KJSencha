<?php

namespace KJSencha\Annotation;

/**
 * Interval annotation
 *
 * Example
 *
 * @Annotation
 * @ExtJS\Interval(seconds=60)
 */
class Interval extends AbstractAnnotation
{
    protected $seconds = 0;

    protected static $time_table = array(
        'seconds' 	=> 1,
        'minutes' 	=> 60,
        'hours'		=> 3600,
    );

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        foreach ($options as $name => $number) {
            if (isset(self::$time_table[$name])) {
                $this->seconds+= ( self::$time_table[$name] * $number );
            }
        }
    }

    /**
     * @return integer
     */
    public function getSeconds()
    {
        return $this->seconds;
    }
}
