# Laravel Enlighten
A seamless package to document your Laravel APIs.

There is no need to add endless docblocks to each API method, maintain tens of readme files, or write extensive wikis to keep your APIs documented and in sync with your codebase!

"Enlighten" will bring some light to your Laravel projects, by creating beautiful documentation from your tests-suites, by doing so, your documentation will always be updated with the current version of your app.

You already invested enough time developing those amazing projects, you don't need to spend more time documenting them, we'll do that for you, you deserve it!

## Introducing Laravel Enlighten
[DASHBOARD PREVIEW IMG]

Just install and run your tests using `phpunit`, that's it! You'll find the entire API documentation in the following URL: `/enlighten/dashboard`

## Usage
After finishing the installation process, run your laravel-tests as usual.

```bash
phpunib
```

That's it! Now you can visit `/enlighten/dashboard` and find your documentation in there.

## Install
Install using composer

```bash
composer install styde/enlighten
```

If you are not using the Laravel package auto-discovery feature, please add the following service-provider to `config/app.php`

```php
[
    'providers' => [
        // ...
        Styde\Enlighten\EnlightenServiceProvider::class,
    ]
];
```

Publish the package assets (css, javascript) to the public folder using artisan:

```bash
php artisan vendor:publish --tag=enlighten-build
```

Optionally, you can publish the config file and views for more customization.

```bash
php artisan vendor:publish --tag=enlighten-config
php artisan vendor:publish --tag=enlighten-views
```

## Database Setup
`Enligthen` uses a secondary database to record the information from your test-suite and generate the documentation.

Add a new database using the same name of your primary-database with an `_enlighten` suffix:

```text
DB_NAME=my_default_database
# my_default_database_enlighten
```

> There no need to add any new entries on your `config/database.php`

After creating the new database, run the migrations using artisan:

```bash
php artisan migrage
```

## Advanced configuration
To "group" your tests-classes as "modules", you can use a regular expression to find all he classes that matches with a given pattern:

```php
// config/enlighten.php
[
    'modules' => [
        [
            'name' => 'Users',
            'pattern' => ['*Users*']
        ],
        [
            'name' => 'Other Modules',
            'pattern' => ['*'],
        ],
    ]
];
```

> It is recommended to have a "catch all" group at the end to include all those files that didn't match with any of the other patterns.

## Excluding test-classes from the documentation
If you want to include all the test-classes and methods in your documentation, you can skip these step, otherwise, you can add the following to the `/config/enlighten.php` file:

```php
[
    'tests' => [
        // Add expressions to exclude test class names and test method names.
        // i.e. Tests\Unit\* excludes all tests in the Tests\Unit\ suite,
        // validates_* excludes all tests that start with validates_.
        'exclude' => [],
    ],
];
```

## Customizing titles and descriptions
If you want to have more control on the titles and descriptions for classes and methods in your documentation, you can use the following annotation in your test files:

```php
/**
 * @testdox User Module
 *
 * @description Manage all the user-related petitions.
 **/
class UsersTest extends TestCase {
    
    /**
     *
     * @testdox Create Users
     *
     * @description Register a new user via POST request. API credentials must be provided.
     **/
    public function testRegisterNewUsers()
    {
        $this->assertTrue(true);
    }
}
```

## Customizing the intro page

To customize the content of your dashboard page, you can add an `ENLIGHTEN.md` markdown file to the root path of your project.
The content of this file will overwrite the default page provided by this package. 

## Credits
- [Duilio Palacios](https://twitter.com/sileence)
- [Jeff Ochoa](https://twitter.com/jeffer_8a)
- [All contributors](https://github.com/styde/enlighten/graphs/contributors)

## License
The MIT License (MIT). Please see [License](https://github.com/styde/enlighten/blob/master/LICENSE.md) File for more information.