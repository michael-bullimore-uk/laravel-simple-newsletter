![Laravel Simple Newsletter logo](art/logo.png)

[!WARNING] This package is currently in development and is not ready for production use. [!WARNING]

## About
A simple Laravel package to save verified user email addresses to your database - a first party solution where you have full control over the data you collect.
- Double opt-in (consent)
- One click unsubscribe
- Automatic deletion of stale unverified subscribers
- Customisable 

## Installation
- `composer require mibu/laravel-simple-newsletter:dev-develop`
- `php artisan vendor:publish --tag=newsletter-install`
- `php artisan migrate`

You can optionally publish the following if you wish to override the defaults.

#### Config
- `php artisan vendor:publish --tag=newsletter-config`

#### Translations
- `php artisan vendor:publish --tag=newsletter-lang`

#### Views
- `php artisan vendor:publish --tag=newsletter-views`

## Documentation
WIP

## Security
Please do not report security related issues publicly.

## License

[MIT License](LICENSE.md)
