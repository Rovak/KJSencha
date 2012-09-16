<?php

namespace KJSencha\Data\Store;

use Zend\Stdlib\Hydrator\HydratorInterface;

class EntityStore extends Store
{
    protected $hydratorClass = 'Zend\Stdlib\Hydrator\ClassMethods';
    protected $hydrator;
    protected $entityClass;

    /**
     * Hydrator
     *
     * @return HydratorInterface The hydrator
     */
    public function getHydrator()
    {
        if (null == $this->hydrator) {
            if ( ! ($hydrator = new $this->hydratorClass) instanceof HydratorInterface) {
                throw new \DomainException('Not a valid Hydrator defined');
            }
            $this->hydrator = $hydrator;
        }

        return $this->hydrator;
    }

    /**
     * Execute the request
     *
     * @param  array  $data [description]
     * @return [type] [description]
     */
    protected function execute(array $data)
    {
        $data = parent::execute($data);

        if ($result = $data->getResult()) {
            foreach ($result as $key => $row) {
                if (is_object($row)) {
                    $result[$key] = $this->getHydrator()->extract($row);
                }
            }
            $data->setResult($result);
        }

        return $data;
    }

    /**
     * Builds entities by there Id and recordData
     *
     * Override this function to implement your own Entity building
     * @return object The entity
     */
    protected function findEntity($id, $data)
    {
        $className = $this->entityClass;

        return $this->getHydrator()->hydrate($data, new $className);
    }

    /**
     * Prepare the data to records
     *
     * @return array
     */
    protected function prepareModifiedRecords(array $data)
    {
        $data = parent::prepareModifiedRecords($data);

        foreach ($data as $id => $record) {
            $data[$id] = $this->findEntity($id, $record);
        }

        return $data;
    }

    /**
     * Prepare the data to records
     *
     * @return array
     */
    protected function prepareNewRecords(array $data)
    {
        $data = parent::prepareNewRecords($data);

        $className = $this->entityClass;

        foreach ($data as $key => $record) {
            $data[$key] = $this->findEntity(null, $record);
        }

        return $data;
    }

    /**
     * Prepare the data to records
     *
     * @return array
     */
    protected function prepareRemovedRecords(array $data)
    {
        $data = parent::prepareRemovedRecords($data);

        $className = $this->entityClass;

        foreach ($data as $id => $record) {
            $data[$id] = $this->findEntity($id, $record);
        }

        return $data;
    }

}
