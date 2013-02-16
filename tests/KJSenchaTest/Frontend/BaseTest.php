<?php

namespace KJSenchaTest\Frontend;

use KJSencha\Frontend\Base;
use PHPUnit_Framework_TestCase;

class BaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KJSencha\Frontend\Base::__construct
     */
    public function testConstructorOverloading()
    {
        $component = new Base('Ext.Component');

        $this->assertEmpty($component->toArray());

        $title = 'Test Title';

        $component = new Base('Ext.Component', array(
            'title' => $title
        ));

        $this->assertArrayHasKey('title', $component->toArray());
        $this->assertEquals($title, $component['title']);

        $component = new Base(array(
            'title' => $title
        ));

        $this->assertArrayHasKey('title', $component->toArray());
        $this->assertEquals($title, $component['title']);
    }
}
