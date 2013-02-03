<?php

namespace KJSenchaTest\Annotation;

use KJSenchaTest\Util\ServiceManagerFactory;
use PHPUnit_Framework_TestCase;

class FormhandlerTest extends PHPUnit_Framework_TestCase
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
     * @covers KJSencha\Annotation\Formhandler::decorateObject
     */
    public function testAnnotationDecoratesMethod()
    {
        /* @var $action \KJSencha\Direct\Remoting\Api\Object\Action */
        $action = $this->apiBuilder->buildAction('KJSenchaTestAsset\Direct\Form\Profile');
        
        $this->assertTrue($action->hasMethod('updateBasicInfo'));
        $this->assertTrue($action->getMethod('updateBasicInfo')->getOption('formHandler'));
    }

    /**
     * @covers KJSencha\Annotation\Formhandler::decorateObject
     */
    public function testAnnotationDoesNotDecorateOthers()
    {
        /* @var $action \KJSencha\Direct\Remoting\Api\Object\Action */
        $action = $this->apiBuilder->buildAction('KJSenchaTestAsset\Direct\Form\Profile');

        $this->assertTrue($action->hasMethod('getBasicInfo'));
        $this->assertNull($action->getMethod('getBasicInfo')->getOption('formHandler'));
    }
}
