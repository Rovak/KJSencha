<?php

namespace KJSencha\Ajax\Store;

use KJSencha\Frontend\Window\MessageBox;
use KJSencha\Frontend\Window\Window;
use KJSencha\Frontend as Ext;
use KJSencha\Data\Store\Store;
use KJSencha\View\Model\ExtJSModel;

/**
 * Represents the REST models
 */
class Overzicht extends Store
{
    public function read()
    {
        $result = new ExtJSModel;
        //$result->exec(MessageBox::alert('Succes!', 'Succesvol opgeslagen'));

        $window = Window::Factory(array(
            'width' => 200,
            'height' => 200
        ));

        //$result->exec('var win = ' . $window->create() . ' win.show()');

        $result->add(array(
            'selector' => '#testpanel',
            'config' => Ext\Panel::Factory(array(
                'region' => 'center',
                'title' => 'Gas',
            ))->toArray()
        ));

        $result->add(array(
            'selector' => '#testpanel',
            'config' => Ext\Panel::Factory(array(
                'region' => 'east',
                'width' => 200,
                'Title' => 'Navigation'
            ))->toArray()
        ));

        return $result;
    }

    public function update($records)
    {

    }

    public function create($records)
    {

    }

    public function delete($records)
    {

    }
}
