<?php

namespace KJSencha\Ajax\Polling;

use KJSencha\Annotation as ExtJS;

class Order
{
    /**
     * @ExtJS\Interval(seconds="10")
     */
    public function newOrders()
    {
        return array(
            'new_orders' => 10,
        );
    }
}
