<?php

namespace KJSencha\Frontend\Window;

use KJSencha\Frontend as Ext;
use KJSencha\Frontend\Component;

class MessageBox extends Component
{
    /**
     * Expression for alert message
     *
     * @param  [type] $title   [description]
     * @param  [type] $message [description]
     * @return [type] [description]
     */
    public static function alert($title, $message)
    {
        return new Ext\Expr("Ext.Msg.alert('$title', '$message')");
    }
}
