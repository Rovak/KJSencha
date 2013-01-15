<?php

namespace KJSenchaTest\Frontend;

use KJSencha\Controller\DirectController;
use KJSenchaTest\Bootstrap;
use PHPUnit_Framework_TestCase;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\Parameters;

class DirectControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    public function setUp()
    {
        $sl = Bootstrap::getServiceManager();

        /* @var $manager DirectManager */
        $manager = $sl->get('kjsencha.direct.manager');
        /* @var $apiFactory \KJSencha\Direct\Remoting\Api\Api */
        $api = $sl->get('kjsencha.api');

        $this->controller = new DirectController($manager, $api);
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'kjsencha_direct'));
        $this->event = new MvcEvent();
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
    }

    function testValidFormResponse()
    {
        $this->request->setPost(new Parameters(array(
            'extAction' => 'KJSenchaTest.Direct.form.Profile',
            'extMethod' => 'getBasicInfo',
            'extTID'    => 0,
            'extModule' => null,
        )));

        // Kick the controller into action
        $result = $this->controller->dispatch($this->request);


        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);
    }

}
