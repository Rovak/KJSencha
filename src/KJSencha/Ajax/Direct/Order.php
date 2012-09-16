<?php

namespace KJSencha\Ajax\Direct;

use KJSencha\Annotation as ExtJS;

/**
 * Customer
 */
class Order
{
    /**
     * Verkrijg het Order ID
     */
    public function getInfo($orderId)
    {
        return "Order Info " . $orderId;
    }

    /**
     * Verkrijg het Order ID
     */
    public function getExtraInfo($orderId)
    {
        return "Order Info " . $orderId;
    }
}
