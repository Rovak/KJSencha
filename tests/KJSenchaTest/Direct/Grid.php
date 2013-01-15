<?php

namespace KJSenchaTest\Direct;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Direct Grid Example
 *
 * Generates row data to output to the Grid example
 *
 * The data will build an ArrayCollection with increasing numbers
 * and the result is being filtered, sorted etc by the Criteria API
 * which is taken from the Doctrine\Common library
 *
 * @see http://docs.doctrine-project.org/en/latest/reference/working-with-associations.html#filtering-collections
 */
class Grid
{
    /**
     * Generated random rows
     *
     * @param array $fields
     * @param integer $num
     * @return ArrayCollection
     */
    protected function getData(array $fields, $num = 50)
    {
        $data = new ArrayCollection();

        for ($i = 0; $i < $num; $i++) {
            $row = array();
            foreach ($fields as $field) {
                $row[$field] = ucfirst($field) . ' ' . $i;
            }
            $data->add((object) $row);
        }

        return $data;
    }

    /**
     * Retrieve the grid data by the given parameters, the Ext JS Store will
     * pass the following parameters:
     *
     *      start:  Where to begin
     *      limit:  How many results to return
     *      sort:   Array of sort values
     *
     * @param array $values
     * @return array
     */
    public function getGrid($values)
    {
        $criteria = Criteria::create()
            ->setFirstResult($values['start'])
            ->setMaxResults($values['limit']);

        // Add sorting
        if (!empty($values['sort'])) {
            $orderBy = array();
            foreach ($values['sort'] as $sort) {
                $orderBy[$sort['property']] = $sort['direction'];
            }
            $criteria->orderBy($orderBy);
        }

        return $this
                ->getData(array('name', 'info'))
                ->matching($criteria)
                ->toArray();
    }
}