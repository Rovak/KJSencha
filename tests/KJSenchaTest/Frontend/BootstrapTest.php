<?php

namespace KJSenchaTest\Frontend;

use KJSencha\Frontend\Bootstrap;
use PHPUnit_Framework_TestCase;

/**
 * @author Marco Pivetta <ocramius@gmail.com>
 */ 
class BootstrapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KJSencha\Frontend\Bootstrap::__construct
     * @covers \KJSencha\Frontend\Bootstrap::getVariables
     * @covers \KJSencha\Frontend\Bootstrap::setVariables
     * @covers \KJSencha\Frontend\Bootstrap::addVariables
     */
    public function testSetGetAddVariables()
    {
        $bootstrap = new Bootstrap(array(
            'variables' => array('key' => 'value'),
        ));

        $this->assertSame(array('key' => 'value'), $bootstrap->getVariables());

        $bootstrap->setVariables(array('key2' => 'value2'));

        $this->assertSame(array('key2' => 'value2'), $bootstrap->getVariables());

        $bootstrap->addVariables(array('key' => 'value'));

        $variables = $bootstrap->getVariables();

        $this->assertArrayHasKey('key', $variables);
        $this->assertArrayHasKey('key2', $variables);
        $this->assertSame('value', $variables['key']);
        $this->assertSame('value2', $variables['key2']);
    }

    /**
     * @covers \KJSencha\Frontend\Bootstrap::__construct
     * @covers \KJSencha\Frontend\Bootstrap::getVariables
     * @covers \KJSencha\Frontend\Bootstrap::addVariables
     */
    public function testAddVariablesMergesVariables()
    {
        $bootstrap = new Bootstrap(array(
            'variables' => array(
                'main' => array(
                    'value0',
                    'value1',
                    'key3' => 'value3',
                    'key4' => 'value4',
                ),
            ),
        ));

        $variables = $bootstrap->getVariables();
        $this->assertArrayHasKey(0, $variables['main']);
        $this->assertSame('value0', $variables['main'][0]);
        $this->assertArrayHasKey('key3', $variables['main']);
        $this->assertSame('value3', $variables['main']['key3']);

        $bootstrap->addVariables(array(
            'main' => array(
                'added2',
                'key4' => 'replaced4',
                'key5' => 'value5',
            ),
            'other' => 'test',
        ));

        $variables = $bootstrap->getVariables();
        $this->assertArrayHasKey(0, $variables['main']);
        $this->assertSame('value0', $variables['main'][0]);
        $this->assertArrayHasKey('key3', $variables['main']);
        $this->assertSame('value3', $variables['main']['key3']);

        $this->assertArrayHasKey(2, $variables['main']);
        $this->assertSame('added2', $variables['main'][2]);
        $this->assertArrayHasKey('key4', $variables['main']);
        $this->assertSame('replaced4', $variables['main']['key4']);
        $this->assertArrayHasKey('key5', $variables['main']);
        $this->assertSame('value5', $variables['main']['key5']);
    }
}
