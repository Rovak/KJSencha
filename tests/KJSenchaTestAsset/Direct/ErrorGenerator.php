<?php

namespace KJSenchaTestAsset\Direct;

use KJSencha\Annotation as Ext;
use Exception;

/**
 * Direct object that generates errors in every method
 */
class ErrorGenerator
{
    /**
     * @Ext\Remotable
     */
    public function throwException()
    {
        throw new Exception("Exception!");
    }

}