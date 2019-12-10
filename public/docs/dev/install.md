# Installation

Digidocu provide simple & easy installation.

## Steps
1. Download the latest release from [here](https://github.com/harish81/digidocu/releases) or clone repository.
2. Run `composer install`.
3. Copy & setup `.env` file.
4. Create database & Change `DB_DATABASE` in `.env`.
5. Migrate the Database `php artisan migrate`.
6. Run `php artisan key:generate`
7. Run `php artisan db:seed` (This will generate super-admin & basic settings [required]).
8. Visit URL in the browser

##### Default Login Credential for super admin
| Username | Password |
|----------|----------|
| super    | 123456   |

## System Requirement
You will need to make sure your server meets the following requirements:
 - PHP >= 7.2.0
 - [Laravel 6](https://laravel.com/docs/6.x#server-requirements)

## Support
If there is any issue with the installation, please report us at 
github [issue](https://github.com/harish81/digidocu/issues).
