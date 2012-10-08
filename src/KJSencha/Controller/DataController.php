<?php

namespace KJSencha\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DataController extends AbstractActionController
{
    public function componentAction()
    {
        $sm = $this->getServiceLocator();
        $componentManager = $sm->get('kjsencha.cmpmgr');

        $component = $componentManager->get($this->params()->fromPost('className'));

        $response = $this->getResponse();
        $response->setContent($component->toJson());

        return $response;
    }
}