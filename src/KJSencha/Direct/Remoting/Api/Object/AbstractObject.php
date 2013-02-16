<?php

namespace KJSencha\Direct\Remoting\Api\Object;

use Serializable;

abstract class AbstractObject implements Serializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $objectName;

    /**
     * @var mixed[]
     */
    private $children = array();

    /**
     * @param string $objectName
     */
    public function __construct($objectName)
    {
        $this->setName($objectName);
        $this->setObjectName($objectName);
    }

    /**
     * Set the name of this object
     *
     * @param string $name name of the object as exposed to the js api
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of this object
     *
     * @param string $objectName name of the object as known in backend
     */
    public function setObjectName($objectName)
    {
        $this->objectName = (string) $objectName;
    }

    /**
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AbstractObject $child
     */
    public function addChild(AbstractObject $child)
    {
        $this->children[] = $child;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        $data = array(
            'name'       => $this->getName(),
            'objectName' => $this->getObjectName(),
            'children'   => $this->getChildren(),
        );

        return serialize($data);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Incorrect unserialized data');
        }

        if (isset($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['objectName'])) {
            $this->setObjectName($data['objectName']);
        }

        if (isset($data['children'])) {
            $this->children = $data['children'];
        }
    }

    /**
     * Retrieve the array options as required by
     * http://www.sencha.com/products/extjs/extdirect/
     *
     * @return array
     */
    abstract public function toApiArray();
}
