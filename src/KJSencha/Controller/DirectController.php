<?php

namespace KJSencha\Controller;

use Exception;
use KJSencha\Direct\DirectManager;
use KJSencha\Direct\Remoting\Api\Api;
use KJSencha\Direct\Remoting\RPC;
use KJSencha\Direct\DirectEvent;
use Zend\Json\Json as JsonFormatter;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model\JsonModel;

/**
 * Direct Controller which executes RPC's
 *
 * The controller returns a format as specified by the official specifications
 * which can be found at http://www.sencha.com/products/extjs/extdirect
 */
class DirectController extends AbstractController
{
    /**
     * @var \KJSencha\Direct\DirectManager
     */
    protected $manager;
    /**
     * @var Api
     */
    protected $api;

    /**
     * @var array
     */
    protected $rpcs;

    /**
     * @var boolean
     */
    protected $debugMode;

    /**
     * @param DirectManager $manager
     * @param Api           $api
     */
    public function __construct(DirectManager $manager, Api $api)
    {
        $this->manager = $manager;
        $this->api = $api;
        $this->setDebugMode(false);

    }

    /**
     * Is it a upload request
     *
     * @return boolean
     */
    public function isUpload()
    {
        return ($this->params()->fromPost('extUpload') === 'true');
    }

    /**
     * Is this a form request
     *
     * @return boolean
     */
    public function isForm()
    {
        return (boolean) $this->params()->fromPost('extAction', false);
    }

    /**
     * @param boolean $debugMode
     */
    public function setDebugMode($debugMode)
    {
        $this->debugMode = (boolean) $debugMode;
    }

    /**
     * @return boolean
     */
    public function isDebugMode()
    {
        return $this->debugMode;
    }

    /**
     * Dispatch controller
     *
     * @param  MvcEvent $mvcEvent
     * @return string
     * @throws Exception
     */
    public function onDispatch(MvcEvent $mvcEvent)
    {
        $result = $this->dispatchRPCS();

        // Build a valid upload response and directly return the result
        if ($this->isForm() && $this->isUpload()) {
            $result = $this->buildFormUploadResponse($result);
            return $this->getResponse()
                 ->setContent($result);
        }

        $mvcEvent->setResult(new JsonModel($result));

        return $mvcEvent;
    }

    /**
     * Build a valid upload response, the response expects the json result
     * to be wrapped with <html><body><textarea>(json)</textarea></body></html>
     *
     * @param string $content
     * @return string Content wrapped in a valid format
     */
    protected function buildFormUploadResponse($content)
    {
        $json = JsonFormatter::encode($content);
        $json = preg_replace("/&quot;/", '\\&quot;', $json);

        return '<html><body><textarea>' . $json . '</textarea></body></html>';
    }

    /**
     * Dispatches the RPCS from the current request and returns the result
     *
     * @return array
     * @throws Exception
     */
    protected function dispatchRPCS()
    {
        $rpcs = $this->getRPC();

        $result = array();

        if ($rpcs instanceof RPC) {
            $result = $this->dispatchRPC($rpcs);
        } elseif (is_array($rpcs)) {
            foreach ($rpcs as $rpc) {
                $result[] = $this->dispatchRPC($rpc);
            }
        } else {
            throw new Exception('Invalid direct request');
        }

        return $result;
    }

    /**
     * Retrieve the RPCS from the request
     *
     * @return array
     */
    protected function getRPC()
    {
        if (null == $this->rpcs) {

            $request = $this->getRequest();

            if ($this->isForm()) {
                $post = $this->params()->fromPost();
                $rpc = array(
                    'action'	=> $post['extAction'],
                    'method'    => $post['extMethod'],
                    'tid'		=> $post['extTID'],
                    'module'    => $post['extModule'],
                    'data'		=> ArrayUtils::merge($post, $this->params()->fromFiles())
                );
                $this->rpcs = RPC::factory($rpc);
            } else {
                $rpcs = array();

                if ($request->getContent()) {
                    $rpcs = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
                } elseif ($this->params()->fromQuery('callback')) {
                    $rpcs = json_decode($this->params()->fromQuery('data'), true);
                }

                // TODO valid json check
                // Convert assoc array to array
                if (ArrayUtils::isHashTable($rpcs)) {
                    $rpcs = array($rpcs);
                }

                $this->rpcs = array();

                foreach ($rpcs as $rpc) {
                    $this->rpcs[] = RPC::factory($rpc);
                }
            }
        }

        return $this->rpcs;
    }

    /**
     * Run the RPC and return its output
     *
     * @param  RPC       $rpc
     * @return array
     * @throws Exception when parameters are not valid
     */
    protected function dispatchRPC(RPC $rpc)
    {
        $response = array(
            'type'      => 'rpc',
            'tid'       => $rpc->getId(),
            'action'    => $rpc->getAction(),
            'method'    => $rpc->getMethod(),
            'result'    => null,
        );

        if (!$this->api->hasAction($rpc->getAction())) {
            throw new Exception('Action ' . $rpc->getAction() . ' does not exist');
        }

        $action = $this->api->getAction($rpc->getAction());

        // Verify the method exists
        if (!$action->hasMethod($rpc->getMethod())) {
            throw new Exception('Method ' . $rpc->getMethod() . ' does not exist');
        }

        // Verify that we received enough parameters to call the method
        if ($action->getMethod($rpc->getMethod())->getNumberOfParameters() > count($rpc->getData())) {
            throw new Exception('Invalid parameter count');
        }

        $object = $this->manager->get($action->getObjectName());

        // Trigger a RPC dispatch event
        $eventVars = array(
            'object' => $object,
            'rpc'    => $rpc,
        );

        $result = $this->getEventManager()->trigger(DirectEvent::EVENT_DISPATCH_RPC, $this, $eventVars);

        if ($result->stopped()) {
            return $result->last();
        }

        try {
            // Fetch result from the function call
            $response['result'] = call_user_func_array(array($object, $rpc->getMethod()), $rpc->getData());
        } catch (Exception $e) {
            $error = array(
                'type'      => 'exception',
                'message'   => 'An unhandled exception occured',
                'where'     => ''
            );

            if ($this->isDebugMode()) {
                $error['message'] = $e->getMessage();
                $error['where'] = $e->getTraceAsString();
            }

            $response['result'] = $error;
        }

        return $response;
    }
}
