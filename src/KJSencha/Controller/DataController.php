<?php

namespace KJSencha\Controller;

use Exception;
use KJSencha\Frontend as Ext;
use KJSencha\Service\ComponentManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class DataController extends AbstractActionController
{
    /**
     * @var \KJSencha\Service\ComponentManager
     */
    protected $componentManager;

    /**
     * @param \KJSencha\Service\ComponentManager $componentManager
     */
    public function __construct(ComponentManager $componentManager)
    {
        $this->componentManager = $componentManager;
    }

    /**
     * Component builder
     *
     * Fetches components from the ComponentManager and converts
     * them to a JSON format
     *
     * The output can be used by Ext.ComponentLoader
     *
     * @return Response
     * @throws Exception
     */
    public function componentAction()
    {
        $response = $this->getResponse();

        try {
            $component = $this->componentManager->get($this->params()->fromPost('className'));
        } catch(Exception $e) {
            // When something goes wrong create a new panel which holds the error message
            $component = new Ext\Panel(array(
                'html'          => 'Exception: ' . $e->getMessage(),
                'bodyPadding'   => 5,
                'bodyStyle'     => 'color: #F00; text-align: center',
                'border'        => 0,
            ));
        }

        $response->setContent($component->toJson());

        return $response;
    }
}
