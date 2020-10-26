# Areas

Each area in Enlighten represents a **test suite**.

We use the generic, less technical, term of "Areas" because one of the objectives of Enlighten
is generating the documentation for your end users, clients or even the QA department.

By default, each area is the second segment of your test class FQN in slug format, i.e.:

`Tests\Feature\CreateUserTest` -> `feature`

`Tests\Unit\UserTest` -> `unit`

## Customise the displayed areas (optional):

If you wish to customise the areas that are displayed in the Enlighten panel, add the `areas` key in your Enlighten config, as an associative or simple array:

```php
// config/enlighten.php
return [
    //...

    'areas' => ['feature', 'unit'],

    // or:
    
    'areas' => ['api' => 'API', 'feature' => 'Feature'],
    
    //...
];
```

Otherwise, leave that config option commented and Enlighten will show all the available areas.

This is a display option, therefore it will not ignore tests. If you wish to ignore tests please refer to our readme file.

## Advanced configuration (optional)

You can also create your own custom area resolver if for any reason your areas / test suites are not the second segment of your test classes.

This is an advanced option, that won't be necessary in most cases.

For example if your area is represented by the forth segment of your test classes instead of the second one, you can add the following logic to a boot method of a Service Provider in your app:

```php
    if (config('enlighten.enabled')) {
        \Styde\Enlighten\Facades\Enlighten::setCustomAreaResolver(function ($className) {
            return explode('\\', $className)[3];
        });   
    }
```

Now `Enlighten::getAreaSlug('Modules\Field\Tests\Feature\FieldGroupTest')` will return 'feature' instead of 'field'.
