# Changelog

All notable changes to `nova-scheduled-jobs` will be documented in this file

## 7.0.0 - 2026.11.15

### Breaking Changes

-   Require PHP 8.2+
-   Require Laravel Nova 5 (`laravel/nova` ^5.0); Nova 4 is no longer supported
-   Require Laravel 12+ (Laravel 10 and 11 are end-of-life; Laravel no longer ships LTS majors)
-   Test against Laravel 12 / 13 via Orchestra Testbench 10–11

### Other Changes

-   CI matrix covers PHP 8.2–8.4 across Laravel 12–13 (PHP 8.2 × Laravel 13 excluded)
-   Prefer Laravel's `Event::normalizeCommand()` when available for command display
-   PHPUnit config updated for PHPUnit 10+

## 6.1.4 - 2026.11.15

-   Fix jobs UI crash when the host app disables JsonResource wrapping (`JsonResource::withoutWrapping()`) by always returning a `{ data: [...] }` payload and defensively parsing the response in the card/tool (#49)
-   Ensure scheduled events are loaded for HTTP requests by bootstrapping the console kernel before reading the schedule (Laravel 10+ / #83)

## 6.1.3 - 2023.08.15

-   Package Updates
-   Added internal dependency configuration via actions and dependabot config file

## 6.1.2 - 2023.04.26

-   Bugfix for missing authentication middlware for api routes

## 6.1.1 - 2023.04.22

-   Package Updates

## 6.1.0 - 2023.03.08

-   Package Updates

## 6.0.2 - 2023.02.03

-   Package Updates

## 6.0.1 - 2022.12.05

-   Readme Updates
-   GitHub Action Updates

## 6.0.0 - 2022.12.05

### Breaking Changes

-   Dropped support for PHP 7
-   Restructured code to be more simplistic requiring changes to service provider, tool, and card
    (see [Readme](https://github.com/llaski/nova-scheduled-jobs/blob/master/README.md) for upgrade,
    just follow normal install instructions)
-   Updated and fixed Authorization middleware structure
-   Routes have changed /nova-scheduled-jobs -> Returns inertiajs component
    /nova-scheduled-jobs/jobs -> Returns json w/ list of jobs /nova-scheduled-jobs/dispatch -> Dispatches command
    -   Temporarily removed localization support. If you need additional support can copy the keys in the resources/lang/en.json file and add them into your own language file.

### Other Changes

-   Removed unneeded dependencies
-   Completely revamped Component, following template from `php artisan nova:tool` command
-   Updated UI to utilize Nova components and methods
-   Updated UI for better dark model support
-   Updated UI to follow conventions from `php artisan schedule:list` command

## 1.1.0 - 2018.08.27

-   Added Nova Tool to display list of scheduled jobs and commands on full page
-   Updated card to 1/2 width and reduced number of fields being displayed
-   Updated readme with better screenshots and instructions for tool

## 1.0.0 - 2018.08.25

-   Initial Release
