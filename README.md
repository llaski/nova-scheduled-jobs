# Nova Scheduled Jobs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/llaski/nova-scheduled-jobs.svg?style=flat-square)](https://packagist.org/packages/llaski/nova-scheduled-jobs)
![Test Suite](https://github.com/llaski/nova-scheduled-jobs/actions/workflows/tests.yml/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/llaski/nova-scheduled-jobs.svg?style=flat-square)](https://packagist.org/packages/llaski/nova-scheduled-jobs)

## Includes both a tool and card to display your scheduled commands and jobs

### Tool

Please note that for the tool there are a large number of columns and depending on the type of scheduled jobs you have you may need to scroll horizontally to see all columns as well as view the actions available which include being able to dispatch certain jobs.

![Nova Scheduled Jobs Tool Screenshot](https://raw.githubusercontent.com/llaski/nova-scheduled-jobs/master/screenshots/Tool.png)

### Card

![Nova Scheduled Jobs Card Screenshot](https://raw.githubusercontent.com/llaski/nova-scheduled-jobs/master/screenshots/Card.png)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via
composer:

```bash
composer require llaski/nova-scheduled-jobs
```

To setup the tool, you must register the tool with Nova. This is typically done in the `tools`
method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php
<?php

namespace App\Providers;

use Llaski\NovaScheduledJobs\Tool as NovaScheduledJobsTool;

// ...

public function tools()
{
    return [
        // ...
        new NovaScheduledJobsTool,
    ];
}
```

To setup the card, you must register the card with Nova. This is typically done in the `cards`
method of your resource or dashboard. For example:

```php
// in app/Nova/Dashboards/Main.php
<?php

namespace App\Nova\Dashboards;

use Llaski\NovaScheduledJobs\Card as NovaScheduledJobsCard;
// ...

public function cards()
{
    return [
        // ...
        new NovaScheduledJobsCard,
    ];
}
```

### Testing

```zsh
vendor/bin/phpunit --verbose
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email larry.laski@gmail.com instead of using the
issue tracker.

## Credits

-   [Larry Laski](https://github.com/llaski)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
