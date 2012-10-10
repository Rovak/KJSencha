<?php

namespace KJSencha\Frontend\Window;

use KJSencha\Frontend as Ext;
use KJSencha\Frontend\Component;

class MessageBox extends Component
{
    /**
     * Expression for alert message
     *
     * @param  string   $title
     * @param  string   $message
     * @return Ext\Expr
     */
    public static function alert($title, $message)
    {
        return new Ext\Expr("Ext.Msg.alert('$title', '$message')");
    }
}
