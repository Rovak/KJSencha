<?php

namespace KJSenchaTest\Frontend;

use KJSencha\Controller\DirectController;
use KJSenchaTest\Util\ServiceManagerFactory;
use PHPUnit_Framework_TestCase;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\Parameters;

class DirectControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \KJSencha\Controller\DirectController
     */
    protected $controller;

    /**
     * @var \Zend\Http\PhpEnvironment\Request
     */
    protected $request;

    /**
     * @var \Zend\Mvc\Router\RouteMatch
     */
    protected $routeMatch;

    /**
     * @var \Zend\Mvc\MvcEvent
     */
    protected $event;

    public function setUp()
    {
        // Used by \KJSencha\Service\ApiFactory::createService
        \Zend\Console\Console::overrideIsConsole(false);
        $sl = ServiceManagerFactory::getServiceManager();

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

    /**
     * @covers \KJSencha\Controller\DirectController::isForm
     * @covers \KJSencha\Controller\DirectController::getRPC
     * @covers \KJSencha\Controller\DirectController::dispatchRPCS
     */
    function testValidFormResponse()
    {
        $this->request->setPost(new Parameters(array(
            'extAction' => 'KJSenchaTestAsset.Direct.form.Profile',
            'extMethod' => 'getBasicInfo',
            'extTID'    => 0,
            'extModule' => null,
        )));

        $result = $this->controller->dispatch($this->request);

        $this->assertTrue($this->controller->isForm());
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);
        $this->assertTrue(is_array($result->result));
        $this->assertEquals('rpc', $result->type);
        $this->assertTrue($result->result['success']);
    }

    /**
     * @covers \KJSencha\Controller\DirectController::buildFormUploadResponse
     * @covers \KJSencha\Controller\DirectController::isUpload
     * @covers \KJSencha\Controller\DirectController::isForm
     * @covers \KJSencha\Controller\DirectController::getRPC
     * @covers \KJSencha\Controller\DirectController::dispatchRPCS
     */
    function testValidUploadResponse()
    {
        $this->request->setPost(new Parameters(array(
            'extAction' => 'KJSenchaTestAsset.Direct.form.Upload',
            'extMethod' => 'emptyUpload',
            'extUpload' => 'true',
            'extTID'    => 0,
            'extModule' => null,
        )));

        $result = $this->controller->dispatch($this->request);

        $this->assertTrue($this->controller->isUpload());
        $this->assertTrue($this->controller->isForm());
        $this->assertInstanceOf('Zend\Http\PhpEnvironment\Response', $result);


        $expectedResult = array(
            'type'      => 'rpc',
            'tid'       => 0,
            'action'    => 'KJSenchaTestAsset.Direct.form.Upload',
            'method'    => 'emptyUpload',
            'result'    => array(),
        );

        /**
         * This matcher checks the following pattern
         * <html><body><textarea>(content)</textarea></body></html>
         */
        $matcher = array(
            'tag' => 'html',
            'descendant' => array(
                'tag' => 'body',
                'children' => array(
                    'count' => 1,
                ),
                'descendant' => array(
                    'tag' => 'textarea',
                    'content' => json_encode($expectedResult)
                )
            )
        );

        $this->assertTag($matcher, $result->getContent());
    }

    /**
     * @covers \KJSencha\Controller\DirectController::setDebugMode
     * @covers \KJSencha\Controller\DirectController::isDebugMode
     */
    function testHiddenErrorResponseWhenDebugModeIsOff()
    {
        $this->request->setPost(new Parameters(array(
            'extAction' => 'KJSenchaTestAsset.Direct.ErrorGenerator',
            'extMethod' => 'throwException',
            'extTID'    => 0,
            'extModule' => null,
        )));

        $result = $this->controller->dispatch($this->request);

        $this->assertEquals('exception', $result->type);
        $this->assertEmpty($result->where);
    }

    /**
     * @covers \KJSencha\Controller\DirectController::setDebugMode
     * @covers \KJSencha\Controller\DirectController::isDebugMode
     */
    function testShowErrorResponseWhenDebugModeIsOn()
    {
        $this->request->setPost(new Parameters(array(
            'extAction' => 'KJSenchaTestAsset.Direct.ErrorGenerator',
            'extMethod' => 'throwException',
            'extTID'    => 0,
            'extModule' => null,
        )));

        $this->controller->setDebugMode(true);

        $result = $this->controller->dispatch($this->request);

        $this->assertEquals('exception', $result->type);
        $this->assertEquals('Exception!', $result->message);
        $this->assertNotEmpty($result->where);
    }
}
