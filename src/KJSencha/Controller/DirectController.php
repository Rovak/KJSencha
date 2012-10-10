<?php

namespace KJSencha\Controller;

use Exception;
use KJSencha\Direct\DirectManager;
use KJSencha\Direct\Remoting\Api\ModuleApi;
use KJSencha\Direct\Remoting\RPC;

use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\Json\Json as JsonFormatter;
use Zend\View\Model\JsonModel;

/**
 * Direct Controller which executes RPC's
 */
class DirectController extends AbstractController
{
    /**
     * @var DirectManager
     */
    protected $manager;

    /**
     * @var ModuleApi
     */
    protected $moduleApi;

    /**
     * @param DirectManager $manager
     * @param ModuleApi $moduleApi
     */
    public function __construct(DirectManager $manager, ModuleApi $moduleApi)
    {
        $this->manager = $manager;
        $this->moduleApi = $moduleApi;
    }

    /**
     * Rpcs
     *
     * @var array
     */
    protected $rpcs;

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
     * Dispatch controller
     *
     * @param MvcEvent $e
     * @return string
     * @throws Exception
     */
    public function onDispatch(MvcEvent $e)
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

        $result = new JsonModel($result);

        // Wrap the result when its a form request
        if ($this->isForm() && $this->isUpload()) {
            $result = '<html><body><textarea>' . JsonFormatter::encode($result) . '</textarea></body></html>';
        }

        $e->setResult($result);

        return $e;
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
                $this->rpcs = RPC::factory(array(
                    'action'	=> $post['extAction'],
                    'method'    => $post['extMethod'],
                    'tid'		=> $post['extTID'],
                    'module'    => $post['extModule'],
                    'data'		=> array_merge($post, $this->params()->fromFiles())
                ));
            } else {
                $rpcs = array();

                if ($request->getContent()) {
                    $rpcs = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], TRUE);
                } elseif ($this->params()->fromQuery('callback')) {
                    $rpcs = json_decode($this->params()->fromQuery('data'), TRUE);
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
     * @param  RPC $rpc
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
            'result'    => NULL,
        );

        if (!$this->moduleApi->hasAction($rpc->getAction())) {
            throw new Exception('Action ' . $rpc->getAction() . ' does not exist');
        }

        $action = $this->moduleApi->getAction($rpc->getAction());

        // Verify the method exists
        if (!$action->hasMethod($rpc->getMethod())) {
            throw new Exception('Method ' . $rpc->getMethod() . ' does not exist');
        }

        // Verify that we received enough parameters to call the method
        if ($action->getMethod($rpc->getMethod())->getNumberOfParameters() > count($rpc->getData())) {
            throw new Exception('Invalid parameter count');
        }

        $object = $this->manager->get($action->getObjectName());
        
        // Fetch result from the function call
        $response['result'] = call_user_func_array(array($object, $rpc->getMethod()), $rpc->getData());

        return $response;
    }

}