<?php

namespace KJSencha\Direct\Polling;

use KJSencha\Direct\Polling\TaskApi;
use Zend\Cache\Storage\StorageInterface;

/**
 * Cached Task API
 *
 * Cached version of the cached task api, delegates to TaskApi
 * when there is no cache hit
 */
class CachedTaskApi implements TaskApiInterface
{
    protected $tasks = array();
    protected $taskApi;
    protected $cache;
    
    /**
     * @param TaskApi $taskApi
     * @param StorageInterface $storage
     */
    public function __construct(TaskApi $taskApi, StorageInterface $storage)
    {
        $this->taskApi = $taskApi;
        $this->cache = $storage;
    }
    
    /**
     * @return StorageInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param StorageInterface $cache
     */
    public function setCache(StorageInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Retrieve tasks from the current given directories
     *
     * @return array
     */
    public function getTasks()
    {
        if ($this->tasks) {
            return $this->tasks;
        }

        $cache = $this->getCache();

        if (false != ($tasks = $cache->getItem('tasks'))) {
            $this->tasks = $tasks;
        } else {
            // Delegate to taskApi scanner
            $this->tasks = $this->taskApi->getTasks();
            $cache->setItem('tasks', $this->tasks);
        }

        return $this->tasks;
    }
}
