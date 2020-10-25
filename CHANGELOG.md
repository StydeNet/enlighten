# Changelog

## v0.3 - 2020-25-10

**To upgrade from v0.2.8 to v0.3, please: 1. delete all the Enlighten tables, 2. Re-run the migrations in your local environment, 3. Delete any published views and 4. Run `php artisan view:clear` to delete the view cache.** 

### New Features
- Support to create code-snippet examples  using the `Enlighten::test()` facade or the `enlighten()` helper in your test methods
- Support to hide keys form JSON responses via config file
- Layout redesign
- Group database queries by request and show the request information associated with each query group.

## Fixes & improvements
- Support older GIT versions
- Support for Laravel `^7.28`
- Support for PHP `^7.3`
- Generate default titles from tests methods with camel case format
- Allow developers to implement their own way to get the "Area" from any test class name
- Allow developers to get info from their own version control system

## v0.2.8 - 2020-10-16

**To upgrade from v0.2.5 to v0.2.6, please delete all the Enlighten tables and re-run the migrations in your local environment** 

- The example group URLs now show the slug of the test class names instead of the ID, for the better readability and to preserve the same URLs after re-running the tests for the same run configuration.
- Add .gitattributes to prevent tests and other unnecessary files from being published.
- Fix for issues: https://github.com/StydeNet/enlighten/issues/12 https://github.com/StydeNet/enlighten/issues/11 and https://github.com/StydeNet/enlighten/issues/8
- Other minor fixes and improvements 

## v0.2 - 2020-10-14

**To upgrade from v0.1 to v0.2, please delete all the Enlighten tables and re-run the migrations in your local environment**

### New features

- Support tests with multiple HTTP requests
- Show exceptions and stack trace on failed / error tests
- Show validation errors in Validation Exceptions
- Show database queries in each test example
- Show an explicit message that Enlighten requires Laravel TestCase in order to work
- Improve database name convention over configuration (learn more in README.md)
- Add "See in Enlighten" link when a test fails (doesn't work with Collision yet)
- Add search bar
- Improved generated titles for test methods that start with `test_`
- Simplify logic in views to allow users to easily customise views

### Fixes && Improvements
- Reset test run to avoid obsolete or repeated information
- Save HTTP request and response as two separate methods, so if the request fails at least we get info from the request
