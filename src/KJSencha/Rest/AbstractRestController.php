<?php

namespace KJSencha\Rest;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\View\Exception\DomainException;
use Zend\View\Model\JsonModel;

abstract class AbstractRestController extends AbstractRestfulController
{
    
    /**
     * Handle the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws DomainException if no route matches in event or invalid HTTP method
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new DomainException('Missing route matches; unsure how to retrieve action');
        }

        $request = $e->getRequest();
        $action  = $routeMatch->getParam('action', false);
        if ($action) {
            // Handle arbitrary methods, ending in Action
            $method = static::getMethodFromAction($action);
            if (!method_exists($this, $method)) {
                $method = 'notFoundAction';
            }
            $return = $this->$method();
        } else {
            // RESTful methods
            switch (strtolower($request->getMethod())) {
                case 'get':
                    if (null !== $id = $routeMatch->getParam('id')) {
                        $action = 'get';
                        $return = $this->get($id);
                        break;
                    }
                    if (null !== $id = $request->getQuery()->get('id')) {
                        $action = 'get';
                        $return = $this->get($id);
                        break;
                    }
                    $action = 'getList';
                    $return = $this->getList();
                    break;
                case 'post':
                    $action = 'create';
                    $return = $this->processPostData($request);
                    break;
                case 'put':
                    $action = 'update';
                    $return = $this->processPutData($request, $routeMatch);
                    break;
                case 'delete':
                    if (null === $id = $routeMatch->getParam('id')) {
                        if (!($id = $request->getQuery()->get('id', false))) {
                            throw new DomainException('Missing identifier');
                        }
                    }
                    $action = 'delete';
                    $return = $this->delete($id);
                    break;
                default:
                    throw new DomainException('Invalid HTTP method!');
            }

            $routeMatch->setParam('action', $action);
        }

        switch ($this->params('format')) {
            case 'json':
            default:
                $contentType = 'application/json';
                $adapter = '\Zend\Serializer\Adapter\Json';
                break;
            case 'sphp':
                $contentType = 'text/plain';
                $adapter = '\Zend\Serializer\Adapter\PhpSerialize';
                break;
        }
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', $contentType);

        $adapter = new $adapter;
        $response->setContent($adapter->serialize($return));
        return $response;
    }
}