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
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    public function setUp()
    {
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

    function testValidFormResponse()
    {
        $this->request->setPost(new Parameters(array(
            'extAction' => 'KJSenchaTestAsset.Direct.form.Profile',
            'extMethod' => 'getBasicInfo',
            'extTID'    => 0,
            'extModule' => null,
        )));

        $result = $this->controller->dispatch($this->request);

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);
        $this->assertTrue(is_array($result->result));
        $this->assertEquals('rpc', $result->type);
        $this->assertTrue($result->result['success']);
    }

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
}
