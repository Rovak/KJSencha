<?php

var_dump(realpath( __DIR__ . '/../KJSenchaTest/Direct'));

return array(
    'kjsencha' => array(
        'direct' => array(
            'modules' => array(
                'KJSenchaTest' => array(
                    'namespace' => 'KJSenchaTest\Direct',
                    'directory' => __DIR__ . '/../../KJSenchaTest/Direct',
                ),
            ),
        ),
    ),
);