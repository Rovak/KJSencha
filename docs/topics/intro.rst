=====
Intro
=====


Installation
============

Download
--------

You can install this module via `composer <https://getcomposer.org/>`_ by running the following
command in your application's root directory:

.. code-block:: c

    $ ./composer.phar require rovak/kj-sencha


Enabling
--------

To enable KJSencha, open your `config/application.config.php` file and add the following items
to the `modules` key:

::

    "AssetManager",
    "KJSencha",

View
----

In your view (presumably `module/Application/view/application/index/index.phtml`), add the
following code (may also be your layout if you prefer so). Those add scripts and css to your
page's head tag:

.. code-block:: php

    <?php
    // first, add extJs to head scripts
    $this->extJs()->loadLibrary();

    // load custom variables set in configuration
    $this->kjSenchaVariables();

    // add loader configuration, which tells where ExtJs classes have to be loaded from
    $this->kjSenchaLoaderConfig();

    // preloads modules required to get the app running
    $this->kjSenchaDirectApi();

    // loads your actual application script (usually at the end of your body tag)
    $this->inlineScript()->appendFile($this->basepath() . '/js/app/app.js');

app.js
------

Now create a new file in `public/js/app/app.js`:

.. code-block:: javascript

    /**
     * KJSencha Example Application
     *
     * @see http://docs.sencha.com/ext-js/4-1/#!/guide/application_architecture
     */
    Ext.application({
        name: 'YourAppName',
        // this variable is inherited from the output of view helper `kjSenchaVariables`
        appFolder: App.basePath + '/js/app',

        launch: function() {
            var name = prompt("Please enter your name", "user");
            KJSencha.echo.greet(name, function(response) {
                alert(response);
            });
        }
    });

Verifying it
------------

You can now browse to your web page. You should be asked for your name and receive a
response computed by the server.

Configuration
-------------

The module itself is just a map of services to be exposed as JS API through
`Ext Direct <http://www.sencha.com/products/extjs/extdirect>`_ via
`Ext.direct.Manager <http://docs.sencha.com/ext-js/4-1/#!/api/Ext.direct.Manager>`_.

Mapping services happens via config key `kjsencha.direct.services`:

.. code-block:: php

    // MyModule/Module.php
    namespace MyModule;

    class Module
    {
        public function getConfig()
        {
            return array(
                'kjsencha' => array(
                    'direct' => array(
                        'services' => array(
                            'My.cool.service.name' => 'my_servicemanager_service_name',
                            'My.other.ServiceName' => 'my_object_repository',
                        ),
                    ),
                ),
            );
        }
    }

This example exposes two services, `My.cool.service.name` and `My.other.ServiceName`.
Public methods of those services can be used in your JS.

Parameters and return types must be one of `string`, `bool`, `int`, `float`, `double` and
`array`s, with arrays being able to contain any of those (type hinting is not yet supported).

Please be careful about exposed functionality, since any public method in the exposed
objects will be available to the user.

Caching
-------

Crawling mapped services and building API definitions to be exposed to the `
Ext.direct.Manager` is a very expensive operation that causes all of the mapped services
to be initialized and crawled via reflection/tokenizers. You may want to enable caching by
defining (in your config) `kjsencha.cache`. `kjsencha.cache` may be any array or traversable
that could be passed to
`Zend\Cache\StorageFactory::factory() <http://framework.zend.com/manual/2.0/en/modules/zend.cache.storage.adapter.html>`_.

Debugging
---------

The default configuration does not show detailed error information when an exception occurs during
a Direct action, this is to prevent unwanted information to be shown in a production environment.
This can be changed by changing the 'debug_mode' to true.

.. code-block:: php

    return array(
        'kjsencha' => array(
            'debug_mode' => true
        )
    );
