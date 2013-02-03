<?php

namespace KJSenchaTest\Direct\Remoting\Api\Factory;

use KJSenchaTest\Util\ServiceManagerFactory;
use PHPUnit_Framework_TestCase;

class ApiBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \KJSencha\Direct\Remoting\Api\Factory\ApiBuilder
     */
    protected $apiBuilder;

    public function setUp()
    {
        $sl = ServiceManagerFactory::getServiceManager();

        $this->apiBuilder = $sl->get('kjsencha.apibuilder');
    }

    /**
     * @covers \KJSencha\Direct\Remoting\Api\Factory\ApiBuilder::buildMethod
     */
    public function testMethodIgnoresConstructor()
    {
        /* @var $action \KJSencha\Direct\Remoting\Api\Object\Action */
        $action = $this->apiBuilder->buildAction('KJSenchaTestAsset\FooService');
        
        $this->assertTrue($action->hasMethod('getBar'));
        $this->assertFalse($action->hasMethod('__construct'));
    }

}
