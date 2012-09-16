<?php

namespace KJSencha\Annotation;

/**
 * @Annotation
 */
class Remotable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        if (isset($options['name'])) {
            $this->setName($options['name']);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return type
     */
    public function hasName()
    {
        return null !== $this->name;
    }
}
