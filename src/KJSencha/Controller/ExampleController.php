<?php

namespace KJSencha\Controller;

use KJSencha\Frontend as Ext;
use ReflectionClass;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ExampleController extends AbstractActionController
{
    protected function getJsClassFolder()
    {
        $class = new ReflectionClass('KJSencha\Module');

        return dirname($class->getFileName()) . '/public/js/classes';
    }

    public function pollingapiAction()
    {
        $sm = $this->getServiceLocator();
        $taskApi = $sm->get('KJSencha\Direct\Polling\TaskApi');
        $taskRunner = $sm->get('KJSencha\Direct\Polling\TaskRunner');
        $taskRunner->setApi($taskApi);

        $taskApi->addDirectory(__DIR__.'/../Ajax/Polling/');

        $taskRunner->runBy(function($task){
            var_dump($task);
        });

        exit;
    }

    public function pollingAction()
    {
        $sm = $this->getServiceLocator();

        $this->layout('kjsencha/example/layout');

        $viewModel = new ViewModel;
        $viewModel->setTemplate('kjsencha/example/polling');

        $viewModel->addChild($sm->get('kjsencha_bootstrap')->getViewModel());

        return $viewModel;
    }

    public function restAction()
    {
        $sm = $this->getServiceLocator();

        $this->layout('kjsencha/example/layout');

        $viewModel = new ViewModel;
        $viewModel->setTemplate('kjsencha/example/rest');
        $viewModel->addChild($sm->get('kjsencha_bootstrap')->getViewModel());

        return $viewModel;
    }

    public function lazygridAction()
    {
        $baseGrid = new Ext\Grid\Panel('TestGrid', array(
            'border'    => false,
        ));

        $baseGrid->addColumn(array(
            'dataIndex' => 'omschrijving',
            'text' => 'Omschrijving',
            'flex' => 1,
        ));

        return new JsonModel($baseGrid->toArray());
    }

    public function gridAction()
    {
        $sm = $this->getServiceLocator();

        $this->layout('kjsencha/example/layout');

        $viewModel = new ViewModel;
        $viewModel->setTemplate('kjsencha/example/grid');

        $viewModel->addChild($sm->get('kjsencha_bootstrap')->getViewModel());

        $baseGrid = new Ext\Grid\Panel('TestGrid', array(
            'title'     => 'TestGrid',
            'width'     => 500,
            'height'    => 300,
        ));

        $baseGrid->addColumn(array(
            'dataIndex' => 'omschrijving',
            'text' => 'Omschrijving',
            'flex' => 1,
            'editor' => array(
                'xtype' => 'textfield',
            )
        ));

        $baseGrid->setExpr('renderTo', 'Ext.getBody()');

        $viewModel->baseGrid = $baseGrid;

        return $viewModel;
    }

    public function directAction()
    {
        $this->layout('kjsencha/example/layout');

        $sm = $this->getServiceLocator();
        $viewRenderer = $sm->get('ViewRenderer');
        $api = $sm->get('kjsencha.api');
        
        print_r($api->getModule('CromvoirtsePortaal')->toArray());
        exit;

        $viewModel = new ViewModel;
        $viewModel->setTemplate('kjsencha/example/direct');

        $viewModel->api = $api;
        $viewModel->addChild($sm->get('kjsencha_bootstrap')->getViewModel());

        $viewRenderer->headScript()
                ->appendScript(file_get_contents($this->getJsClassFolder() . '/KJSencha/data/AjaxListener.js'))
                ->appendScript(file_get_contents($this->getJsClassFolder() . '/KJSencha/data/Factory.js'))
                ->appendScript(file_get_contents($this->getJsClassFolder() . '/KJSencha/direct/ModuleRemotingProvider.js'));

        return $viewModel;
    }

    /**
     * Shows the mapper
     *
     * @return [type] [description]
     */
    public function mapperAction()
    {
        $this->layout('kjsencha/example/layout');

        $viewModel = new ViewModel;
        $viewModel->setTemplate('kjsencha/example/mapper');

        $viewModel->mapper =  new Ext\Base('KJErp.data', array(
            'extend' => 'Ext.Component',
            'singleton' => true,
            'restPath' => 'restpath',
            'servicePath' => 'servicePath',
            'restParameters' => array(
                'module' => 'KJErp',
            ),
            'serviceParameters' => array(
                'module' => 'KJErp',
            )
        ));

        return $viewModel;
    }
}
