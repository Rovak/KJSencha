<?php

namespace KJSencha\Controller;

use Exception;
use KJSencha\Frontend as Ext;
use Zend\Mvc\Controller\AbstractActionController;

class DataController extends AbstractActionController
{
    /**
     * Component builder
     *
     * Fetches components from the ComponentManager and converts
     * them to a JSON format
     *
     * The output can be used by Ext.ComponentLoader
     *
     * @return \Zend\Http\Response
     * @throws Exception
     */
    public function componentAction()
    {
        $response = $this->getResponse();
        $sm = $this->getServiceLocator();
        /* @var $componentManager \KJSencha\Service\ComponentManager */
        $componentManager = $sm->get('kjsencha.componentmanager');

        try {
            $component = $componentManager->get($this->params()->fromPost('className'));
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
