<?php

namespace KJSencha\Controller;

use KJSencha\Data\Rest\Router as RestRouter;
use KJSencha\Data\Store\Router as StoreRouter;
use KJSencha\Direct\Polling\CachedTaskApi;
use KJSencha\Direct\Polling\TaskApi;
use Zend\Cache\StorageFactory;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Handles Ext JS requests
 */
class DataController extends AbstractActionController
{
    /**
     * Restfull requests
     */
    public function restAction()
    {
        $sm = $this->getServiceLocator();
        $configuration = $sm->get('kjsencha.config');

        $router = new RestRouter($configuration['rest']);
        $route = $router->match($this->getRequest());
        $model = $sm->get($route['className']);

        $result = $model->dispatch($this->getRequest(), $this->getResponse());
        
        if (!$result instanceof JsonModel) {
            $result = new JsonModel($result);
        }

        return $result;
    }

    /**
     * Restfull requests
     */
    public function serviceAction()
    {
        $sm = $this->getServiceLocator();
        $configuration = $sm->get('extjsconf');

        $router = new StoreRouter($configuration['store']);
        $route = $router->match($this->getRequest());
        $store = $sm->get($route['className']);

        $result = $store->dispatch($this->getRequest(), $this->getResponse());

        if (! $result instanceof JsonModel) {
            $result = new JsonModel($result);
        }

        return $result;
    }

    /**
     * Direct RemotingProvider actions
     */
    public function pollingAction()
    {
        $sm = $this->getServiceLocator();
        $api = $sm->get('kjsencha.task.api');

        $data = $this->getRequest()->getQuery()->toArray();
        $data = array_merge($data, $this->getRequest()->getPost()->toArray());
        
        $interval = ( $data['interval'] / 1000 );

        // Latency in milliseconds, add this to make up for long requests
        if (isset($data['latency'])) {
            $interval+= round($data['latency'] / 1000);
        }

        $elapsedTime = (int) date('U') - (int) $data['start'];

        $taskRunner = $sm->get('kjsencha.task.runner');

        $result = $taskRunner->runBy(function($task) use ($elapsedTime, $interval) {
            return ( ( $elapsedTime % $task->getInterval() ) < $interval );
        });

        return new JsonModel($result);
    }
}
