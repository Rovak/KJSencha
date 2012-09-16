<?php

namespace KJSencha\Direct\Polling;

use Exception;
use InvalidArgumentException;
use KJSencha\Common\AbstractService;
use KJSencha\Direct\Polling\TaskApiInterface;

/**
 * Task runner
 */
class TaskRunner extends AbstractService
{
    /**
     * @var TaskApiInterface
     */
    protected $api;

    /**
     * @param TaskApiInterface $api
     */
    public function __construct($api = null)
    {
        if ($api instanceof TaskApiInterface) {
            $this->setApi($api);
        }
    }

    /**
     * @param TaskApiInterface $api
     */
    public function setApi(TaskApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @return TaskApiInterface
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Run tasks by evaluating a given function, if this
     * returns true then execute the task and append it to the result
     *
     * @param  callable $func A callable function / closure
     * @return array    Combined results of all tasks which are run
     */
    public function runBy($func)
    {
        if (!is_callable($func)) {
            throw new InvalidArgumentException('Requires valid callback');
        }

        $result = array();

        foreach ($this->api->getTasks() as $id => $task) {
            if (true === $func($task)) {
                $taskResult = $this->executeTask($task);
                $result[] = array_merge($taskResult->toArray(), array(
                    'id' => $id,
                ));
            }
        }

        return $result;
    }

    /**
     * Execute a task
     *
     * @param  Task   $task Task
     * @return Event
     */
    public function executeTask(Task $task)
    {
        $sl = $this->getServiceLocator();
        $objectTask = $sl->get($task->getClassname());
        $method = $task->getMethod();

        if (!method_exists($objectTask, $method)) {
            throw new Exception('invalid action');
        }

        $result = $objectTask->$method();

        if (!$result instanceof Event) {
            $result = new Event('message', $result);
        }

        return $result;
    }

}
