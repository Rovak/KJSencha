<?php

namespace KJExtJs\Data\Rest;

use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Rest Model
 *
 * Represents the REST models
 */
abstract class ObjectModel extends AbstractModel
{
    protected $hydratorClass = 'Zend\Stdlib\Hydrator\ClassMethods';
    protected $hydrator;

    /**
     * Hydrator
     *
     * @return HydratorInterface The hydrator
     */
    public function getHydrator()
    {
        if (null == $this->hydrator) {
            if ( ! ($hydrator = new $this->hydratorClass) instanceof HydratorInterface) {
                throw new \DomainException('Invalid hydrator defined');
            }
            $this->hydrator = $hydrator;
        }

        return $this->hydrator;
    }
}
