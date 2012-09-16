<?php

namespace KJExtJs\Data;

use KJSencha\View\Model\ExtJSModel;
use Zend\Mvc\Controller\AbstractController;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

/**
 * Base AJAX Controller
 *
 * Gathers GET, POST and Raw post data and merges this together to one data array
 */
abstract class AjaxController extends AbstractController
{
    // Fires the event with the parsed values
    protected function execute(array $data)
    {
        return $data;
    }

    /**
     * Dispatch ajax request
     *
     * @param  Request $request  [description]
     * @param  [type]  $response [description]
     * @return [type]  [description]
     */
    public function dispatch(Request $request, Response $response = null)
    {
        $data = $request->getQuery()->toArray();

        if ($request->isPost()) {
            // Merge Post data with the query data
            $data = ArrayUtils::merge($data, $request->post()->toArray());

            // Merge additional JSON data with the data
            if (strpos($request->server()->get('CONTENT_TYPE'), 'json') !== FALSE) {
                $data = ArrayUtils::merge($data, json_decode($request->getContent(), TRUE));
            }
        }

        // TODO put this somewhere else
        $result = $this->execute($data);

        if (is_array($result)) {
            $result = new ExtJSModel($result);
        }

        return $result;
    }
}
