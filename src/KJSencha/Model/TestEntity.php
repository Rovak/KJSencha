<?php

namespace KJSencha\Model;

class TestEntity
{
    protected $id;

    protected $omschrijving;

    public function __construct($id = NULL, $omschrijving = NULL)
    {
        $this->id = $id;
        $this->omschrijving = $omschrijving;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOmschrijving()
    {
        return $this->omschrijving;
    }

    public function setOmschrijving($omschrijving)
    {
        $this->omschrijving = $omschrijving;
    }

}
