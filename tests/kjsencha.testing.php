<?php

return array(
    'service_manager' => array(
        'invokables' => array(
            'echo' => 'KJSenchaTestAsset\Service\EchoService',
        ),
    ),
    'kjsencha' => array(
        'direct' => array(
            'modules' => array(
                'KJSenchaTestAsset' => array(
                    'namespace' => 'KJSenchaTestAsset\Direct',
                    'directory' => __DIR__ . '/KJSenchaTestAsset/Direct',
                ),
            ),
        ),
    ),
);