# Changelog

## v0.2 - 2020-10-14

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
