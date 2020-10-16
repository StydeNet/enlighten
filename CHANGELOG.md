# Changelog

## v0.2.7 - 2020-10-16

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
