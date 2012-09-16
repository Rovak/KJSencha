<?php

namespace KJSencha\Direct\Polling;

/**
 * A polling event
 *
 * @see http://docs.sencha.com/ext-js/4-1/#!/api/Ext.direct.Event
 */
class Event
{
    protected $data = '';
    protected $name = 'message';
    protected $type = 'event';

    /**
     * @param string $name
     * @param string $data
     */
    public function __construct($name = null, $data = null)
    {
        if ($name) {
            $this->setName($name);
        }

        if ($data) {
            $this->setData($data);
        }
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function toArray()
    {
        return array(
            'data' => $this->getData(),
            'type' => $this->getType(),
            'name' => $this->getName(),
        );
    }
}
