<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Setup

You must have headless chrome and chromedriver setup. Set path to chromedriver in the env variables.

## Workers
This simple setup is not as robust as using supervisord, but it's much simpler:

Run worker as daemon:
`php artisan queue:work &`

Turn off worker (restart does not work, so it just shuts down):
`php artisan queue:restart`

## Writing tests

Using browser kit tests instead of dusk. Tests are faster and as reliable, since we're using almost no JS.

https://github.com/laravel/browser-kit-testing#interacting-with-your-application