<?php

return array(

    /**
     * Ext JS Configuration
     */
    'kjsencha' => array(
        'direct' => array(
            'cache' => false,
            'modules' => array(
//                'KJSencha' => array(
//                    'namespace' => 'KJSencha\Ajax\Direct',
//                    'directory' => __DIR__ . '/../src/KJSencha/Ajax/Direct',
//                ),
            ),
        ),

        'bootstrap' => array(
            'default' => array(
                'modules' => array(
//                    'KJSencha'
                ),
                'paths' => array(
                    // Path is relative since it has been mapped in the asset resolvers
                    'KJSencha' => 'js/classes/KJSencha',
                ),
                'requires' => array(
                    'KJSencha.direct.ModuleRemotingProvider',
                ),
            ),
        ),

        /**
         * Cache configuration
         */
        'cache' => array(
            'adapter'	=> array(
                'name' => 'filesystem',
                'options' => array(
                    'cachedir'              => 'data/cache/',
                    'ttl'                   => 3600,
                    'namespace'             => 'kjsencha',
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
                'serializer'
            )
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view/'
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    /**
     * Router
     */
    'router' => array(
        'routes' => array(
            'kjsencha-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/kjsencha/rpc/',
                    'defaults' => array(
                        'controller' => 'kjsencha_direct',
                    ),
                ),
            ),
        ),
    ),

    /**
     * AssetManager config to allow serving files from the `public` dir in this module
     */
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                'KJSencha' => __DIR__ . '/../public',
            ),
        ),
    ),
);
