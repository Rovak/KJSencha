<?php

return array(

    /**
     * Ext JS Configuration
     */
    'kjsencha' => array(
        'rest' => array(
            'modules' => array(
                'KJSencha' => 'KJSencha\Ajax\Rest',
            ),
        ),
        'direct' => array(
            'modules' => array(
                'KJSencha' => array(
                    'namespace' => 'KJSencha\Ajax\Direct',
                    'directory' => __DIR__ . '/../src/KJSencha/Ajax/Direct',
                ),
            ),
            'cache' => false,
        ),
        'polling' => array(
            'modules' => array(
                'KJSencha' => array(
                    'directory' => __DIR__.'/../src/KJSencha/Ajax/Polling/',
                ),
            )
        ),
        'store' => array(
            'modules' => array(
                'KJSencha' => 'KJSencha\Ajax\Store',
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
                    'ttl'                   => 60,
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

    'view_helpers' => array(
        'invokables' => array(
            'kjsencha' => 'KJSencha\View\Helper\ExtJS',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'kjsencha_direct' => 'KJSencha\Controller\DirectController',
            'kjsencha_example' => 'KJSencha\Controller\ExampleController',
            'kjsencha_extjs' => 'KJSencha\Controller\ExtJsController',
            'kjsencha_data' => 'KJSencha\Controller\DataController',
        ),
    ),

    /**
     * Router
     */
    'router' => array(
        'routes' => array(
            'kjsencha-example' => array(
                'type' => 'Segment',
                'priority' => 102,
                'options' => array(
                    'route' => '/kjsencha/example/:action',
                    'defaults' => array(
                        'controller' => 'kjsencha_example',
                        'action' => 'index',
                    ),
                ),
            ),
            'kjsencha-direct' => array(
                'type' => 'Segment',
                'priority' => 101,
                'options' => array(
                    'route' => '/kjsencha/rpc/',
                    'defaults' => array(
                        'controller' => 'kjsencha_direct',
                    ),
                ),
            ),
            'kjsencha-data' => array(
                'type' => 'Segment',
                'priority' => 101,
                'options' => array(
                    'route' => '/kjsencha/data/:action',
                    'defaults' => array(
                        'controller' => 'kjsencha_data',
                        'action' => 'index',
                    ),
                ),
            ),
            'kjsencha' => array(
                'type' => 'Segment',
                'priority' => 100,
                'options' => array(
                    'route' => '/kjsencha/:action',
                    'defaults' => array(
                        'controller' => 'kjsencha_extjs',
                        'action' => 'index',
                    ),
                ),
            ),

        ),
    ),
);
