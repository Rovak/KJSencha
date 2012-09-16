<?php

namespace KJSencha\Ajax\Store;

use KJSencha\Model\TestEntity;
use KJSencha\Data\Store\EntityStore;
/**
 * Represents the REST models
 */
class EntityOverzicht extends EntityStore
{
    protected $entityClass = 'KJSencha\Model\TestEntity';

    public function read()
    {
        return array(
            new TestEntity(1, 'Test Omschrijving 1'),
            new TestEntity(2, 'Test Omschrijving 2'),
        );
    }

    public function update($records)
    {
        return $records;
    }

    public function create($records)
    {

    }

    public function delete($records)
    {

    }
}
