# Component Generator

You can define server-side components in a ServiceManager like this:

```php
    'components' => array(
    	'invokables' => array(
    		'Application.php.Window' => 'Application\ExtJs\Window'
    	),
        'factories' => array(
            'TestComponent' => function($sm) {
                return new Ext\Panel(array(
                    'title' => 'Test Component'
                ));
            }
        )
    ),
```

And then on the client-side you can load the windows with the Ext.ComponentLoader like this:

```javascript
Ext.create('Ext.Window', {
    width: 800,
    height: 400,
    loader: {
        url : App.basePath + '/kjsencha/data/component',
        renderer: 'component',
        params: {
            className: 'Application.php.Window'
        },
        autoLoad: true
    }
}).show();
```

or

```javascript
// Application.data == KJSencha.data.Factory
Ext.create('Ext.Window', {
    width: 800,
    height: 400,
    loader: Application.data.createCmpLoader('Application.php.Window')
}).show();
```