<?php

namespace KJSencha\Frontend\Grid;

use KJSencha\Frontend\Component;

class Panel extends Component
{
    protected $attributes = array(
        'extend'	=> 'Ext.grid.Panel',
        'xtype'		=> 'gridpanel',
        'colModel'	=> array(
            'items' => array(),
        )
    );

    /**
     * Add a grid column
     *
     * @param  string $name
     * @param  array  $attrs
     * @return Panel
     */
    public function addColumn($name, $attrs = null)
    {
        if (is_array($name)) {
            $column = $name;
        } else {
            $column = array();
            $column['text'] = $name;

            if (is_array($attrs)) {
                $column+= $attrs;
            } elseif (is_string($attrs)) {
                $column['dataIndex'] = $attrs;
            }
        }

        $this->attributes['colModel']['items'][] = $column;

        return $this;
    }
}
