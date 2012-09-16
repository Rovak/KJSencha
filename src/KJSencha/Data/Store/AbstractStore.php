<?php

namespace KJSencha\Data\Store;

use KJSencha\Data\AjaxController;
use KJSencha\View\Model\ExtJSModel;
use Zend\Stdlib\ArrayUtils;

/**
 * Represents the REST models
 */
abstract class AbstractStore extends AjaxController
{
    abstract public function read();
    abstract public function update($records);
    abstract public function create($records);
    abstract public function delete($records);

    // Proxy configuration
    protected $limitParam 	= 'limit';
    protected $sortParam 	= 'sort';
    protected $startParam 	= 'start';
    protected $pageParam 	= 'page';
    protected $idParam 		= 'id';
    protected $rootParam 	= 'rows';

    // Store parmaters
    private $offset = 0;
    private $limit;
    private $page = 1;
    private $direction = 'ASC';

    // Fires the event with the parsed values
    protected function execute(array $data)
    {
        if (isset($data[$this->pageParam])) {
            $this->page = $data[$this->pageParam];
        }

        if (isset($data[$this->startParam])) {
            $this->offset = $data[$this->startParam];
        }

        if (isset($data[$this->limitParam])) {
            $this->limit = $data[$this->limitParam];
        }

        if (isset($data[$this->sortParam])) {
            $this->direction = $data[$this->sortParam];
        }

        $method = 'read';

        if (isset($data['xaction'])) {
            $method = $data['xaction'];
        }

        // Tussen de beschikbare acties lopen
        switch (strtolower($method)) {

            /**
             * Update records
             */
            case 'update':
                $recordData = $this->prepareModifiedRecords($data[$this->rootParam]);
                $result = $this->update($recordData);
                break;

            /**
             * Remove records
             */
            case 'delete':
                $recordData = $this->prepareRemovedRecords($data[$this->rootParam]);
                $result = $this->delete($recordData);
                break;

            /**
             * Create new records
             */
            case 'create':
                $recordData = $this->prepareNewRecords($data[$this->rootParam]);
                $result = $this->create($recordData);
                break;

            default:
                $result = $this->$method($data);
                break;
        }

        if (is_array($result)) {
            $result = new ExtJSModel(array(
                'success'           => true,
                $this->rootParam    => array_values($result),
            ));
        }

        return $result;
    }

    /**
     * Prepare the data to records
     *
     * @return array
     */
    protected function prepareModifiedRecords(array $data)
    {
        if (ArrayUtils::isHashTable($data)) {
            $data = array($data);
        }

        $result = array();

        foreach ($data as $record) {
            $result[$record[$this->idParam]] = $record;
        }

        return $result;
    }

    /**
     * Prepare the data to records
     *
     * @return array
     */
    protected function prepareNewRecords(array $data)
    {
        if (ArrayUtils::isHashTable($data)) {
            $data = array($data);
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
        if (ArrayUtils::isHashTable($data)) {
            $data = array($data);
        }

        $result = array();

        foreach ($data as $record) {
            $result[$record[$this->idParam]] = $record;
        }

        return $result;
    }

    /**
     * The number from which to start selecting records
     *
     * @return integer
     */
    public function getOffset()
    {
        return (int) $this->offset;
    }

    /**
     * @return string
     */
    public function getSortDirection()
    {
        return $this->direction;
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return (int) $this->limit;
    }

    /**
     * @return integer
     */
    public function getPage()
    {
        return (int) $this->page;
    }
}
