<?php

namespace KJSencha\Direct\Remoting\Api\Object;

abstract class AbstractObject
{
    private $name;
    private $objectName;
    private $children = array();

    /**
     * @param type $objectName
     */
    public function __construct($objectName)
    {
        $this->setName($objectName);
        $this->setObjectName($objectName);
    }

    /**
     * Set the name of this object
     *
     * @param string $name Objectname
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @param string $name Objectname
     */
    protected function setObjectName($objectName)
    {
        $this->objectName = $objectName;
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
     * @return array
     */
    public function toArray()
    {
        return array(
            'name'          => $this->getObjectName(),
            'objectName'    => $this->getObjectName(),
        );
    }

    /**
     * Retrieve the array options as required by
     * http://www.sencha.com/products/extjs/extdirect/
     *
     * @return array
     */
    abstract public function toApiArray();
}
