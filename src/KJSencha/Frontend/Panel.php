<?php

namespace KJSencha\Frontend;

/**
 * Simple panel
 */
class Panel extends Component
{
    protected $attributes = array(
        'extend' => 'Ext.Panel',
        'xtype'  => 'panel',
        'layout' => 'fit',
    );
}
