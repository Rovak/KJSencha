<?php

namespace KJSencha\Data;

use KJSencha\View\Model\ExtJSModel;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;

/**
 * Base AJAX Controller
 */
abstract class AbstractDataController extends AbstractController
{
    /**
     * Execute the request
     * 
     * @param MvcEvent $e
     * @return ExtJSModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $request = $e->getRequest();
        $data = $request->getQuery()->toArray();

        if ($request->isPost()) {
            // Merge Post data with the query data
            $data = ArrayUtils::merge($data, $request->post()->toArray());

            // Merge additional JSON data with the data
            if (strpos($request->server()->get('CONTENT_TYPE'), 'json') !== FALSE) {
                $data = ArrayUtils::merge($data, json_decode($request->getContent(), TRUE));
            }
        }

        $result = $this->execute($data);

        if (is_array($result)) {
            $result = new ExtJSModel($result);
        }

        return $result;
    }
}
