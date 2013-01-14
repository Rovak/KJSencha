<?php

namespace KJSenchaTest\Frontend;

use KJSencha\Frontend\Component;
use PHPUnit_Framework_TestCase;

class ComponentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KJSencha\Frontend\Component::__construct
     */
    public function testSetComponentNameInConstructor()
    {
        $name = 'Ext.Component';

        $component = new Component($name);

        $this->assertEquals($name, $component->getClassName());

        $component = new Component(array(
            'className' => $name
        ));

        $this->assertEquals($name, $component->getClassName());

        $component = new Component($name, array(
            'extend' => 'Ext.Panel'
        ));

        $this->assertEquals($name, $component->getClassName());
    }
}
