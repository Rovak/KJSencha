<?php

namespace KJSencha\Annotation;

use KJSencha\Direct\Remoting\Api\Object\Method;

/**
 * Enables a method to handle form requests
 * 
 * @Annotation
 */
class Formhandler extends AbstractAnnotation
{
    public function decorateObject($object)
    {
        if ($object instanceof Method) {
            $object->setOption('formHandler', true);
        }
    }
}
