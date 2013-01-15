# Forms

## Basic usage

Forms can be loaded and submitted by pointing the API settings to a Direct method as follows in javascript:

```javascript
var basicInfo = Ext.create('Ext.form.Panel', {
    api: {
        // The server-side method to call for load() requests
        load: Application.Direct.form.Profile.getBasicInfo,
        // The server-side must mark the submit handler as a 'formHandler'
        submit: Application.Direct.form.Profile.updateBasicInfo
    },
    defaultType: 'textfield',
    items: [{
        fieldLabel: 'Name',
        name: 'name'
    },{
        fieldLabel: 'Email',
        msgTarget: 'side',
        name: 'email'
    },{
        fieldLabel: 'Company',
        name: 'company'
    }]
    // A valid form requires more properties which
    // are hidden to keep this example simple
});

basicInfo.getForm().load();
```

In PHP you can then define classes which handle the form requests.

```php
namespace Application\Direct\Form;

use KJSencha\Annotation as Ext;

class Profile
{
    /**
     * Basic information
     *
     * @return array
     */
    public function getBasicInfo()
    {
        return array(
            'success' => true,
            'data' => array(
                'name' => 'Roy van Kaathoven',
                'email' => 'opensource@kj.nu',
                'company' => 'KJ Business Software',
            )
        );
    }

    /**
     * Update basic information
     *
     * @Ext\Formhandler
     */
    public function updateBasicInfo($values)
    {
        return array(
            'errors' => array(
                'name' => 'Wrong info!'
            )
        );
    }
}
```

The `getBasicInfo()` method returns the data that fills the form, the returned data
must stick to the following format in order to create a valid response:

```php
return array(
    'success' => true,
    'data' => array(
        'fieldname' => 'value',
    )
);
```

- `success` marks the response valid or invalid, you can use this to listen to the `failure` or `success` events on the javascript side.
- `data` is a simple array in which the key is the fieldname and the value is the new field value.

After loading the form and changing the data you can submit the form back using the `updateBasicInfo` method.
Notice the `@Ext\Formhandler` annotation which is used to mark the Direct method as a formhandler, without this you won't be able
to create valid form requests.

If your response array contains an `error` key with an array then the key is linked to the fieldname and the value is the errormessage
which will appear in the fields which is usually presented next to the label with a red underline.

## Handling uploads

You can upload files without any modifications, the files will appear in the `$_FILES` global as usual.