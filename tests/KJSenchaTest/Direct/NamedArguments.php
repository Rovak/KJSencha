<?php

namespace KJSenchaTest\Direct;

/**
 * Named Arguments example
 */
class NamedArguments
{
    /**
     * Key => Value pair of arguments
     *
     * @param array $values
     * @return string
     */
    public function showDetails(array $values)
    {
        return sprintf(
            'Hi %s %s, you are %d years old',
            $values['firstName'],
            $values['lastName'],
            $values['age']
        );
    }
}