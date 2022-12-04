# Changelog

All notable changes to `nova-scheduled-jobs` will be documented in this file

## 6.0.0 - 2022-12-05

### Breaking Changes

-   Dropped support for PHP 7
-   Restructured code to be more simplistic requiring changes to service provider, tool, and card
    (see [Readme](https://github.com/llaski/nova-scheduled-jobs/blob/master/README.md) for upgrade,
    just follow normal install instructions)
-   Updated and fixed Authorization middleware structure

### Other Changes

-   Removed unneeded dependencies
-   Completely revamped Component, following template from `php artisan nova:tool` command
-   Updated UI to utilize Nova components and methods
-   Updated UI for better dark model support
-   Updated UI to follow conventions from `php artisan schedule:list` command

## 1.1.0 - 2018-08-27

-   Added Nova Tool to display list of scheduled jobs and commands on full page
-   Updated card to 1/2 width and reduced number of fields being displayed
-   Updated readme with better screenshots and instructions for tool

## 1.0.0 - 2018-08-25

-   Initial Release
