<?php

use KJSencha\Frontend as Ext;

return array(

    /**
     * Ext JS Configuration
     */
    'kjsencha' => array(
        // Path from which ExtJs should be loaded
        'library_path'   => 'http://cdn.sencha.io/ext-4.1.1-gpl/',
        'js'             => array(
            'ext' => 'ext-all.js',
        ),
        'css'            => array(
            'ext' => 'resources/css/ext-all.css',
        ),

        'direct' => array(
            'modules' => array(),
            'services' => array(
                'KJSencha.echo' => 'kjsencha.echo',
            ),
        ),

        'bootstrap' => array(
            'default' => array(
                'modules' => array(
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
                'name' => 'memory',
                'options' => array(),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => true),
                'serializer'
            )
        ),

        'cache_key' => 'module_api',
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
            'kjsencha-data' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/kjsencha/data/[:action]',
                    'defaults' => array(
                        'controller' => 'kjsencha_data',
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
