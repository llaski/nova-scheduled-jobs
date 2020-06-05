# Nova Scheduled Jobs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/llaski/nova-scheduled-jobs.svg?style=flat-square)](https://packagist.org/packages/llaski/nova-scheduled-jobs)
[![Total Downloads](https://img.shields.io/packagist/dt/llaski/nova-scheduled-jobs.svg?style=flat-square)](https://packagist.org/packages/llaski/nova-scheduled-jobs)

## Includes a tool and a few cards to display your scheduled commands and jobs

![Nova Scheduled Jobs Tool Screenshot](https://raw.githubusercontent.com/llaski/screenshots/master/nova-scheduled-jobs-tool.png)

![Nova Scheduled Jobs Card Screenshot](https://raw.githubusercontent.com/llaski/screenshots/master/nova-scheduled-jobs-card.png)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require llaski/nova-scheduled-jobs
```

To setup the tool, you must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        //new \Llaski\NovaScheduledJobs\NovaScheduledJobsTool,
        \Llaski\NovaScheduledJobs\NovaScheduledJobsTool::make(),
        //\Llaski\NovaScheduledJobs\NovaScheduledJobsTool::make()->hide(),
    ];
}
```

To setup the card, you must register the card with Nova. This is typically done in the `cards` method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function cards()
{
    return [
        // ...
        new \Llaski\NovaScheduledJobs\NovaScheduledJobsCard,
    ];
}
```

Alternatively, if you'd rather render the card used in the tool, on your dashboard;

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function cards()
{
    return [
        // ...
        new \Llaski\NovaScheduledJobs\NovaScheduledJobsManagerCard,
    ];
}
```
### Authorisation

All cards and routes consult the tool for permissions, this means you create your permission handler by defining it in the `canSee` callback on the tool;

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        \Llaski\NovaScheduledJobs\NovaScheduledJobsTool::make()
            ->canSee(
                function($request)
                { 
                    return false; 
                }
            ),
    ];
}
```

### Testing

``` bash
phpunit
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email larry.laski@gmail.com instead of using the issue tracker.

## Credits

- [Larry Laski](https://github.com/llaski)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
