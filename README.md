Laravel Modules
===============

A package to add modules to a [Laravel](https://laravel.com/) 5 application. This package allows you to separate out code for parts of your application into their own dedicated "modules" or folders, allowing all code related to a specific section or function of your application to be stored in one place.

Currently supports:
 - Controllers
 - Migrations
 - Models
 - Routes, web and api
 - Service Providers
 - Views

Currently not supported:
 - Database Seeding

This package will soon have commands to assist in making each of these.
 
## Example
An admin panel at domain.com/admin - all functionality related to the admin panel could be turned into a module and stored together in a single location.

Example Structure

    modules/admin
     - modules/admin/controllers
     - - modules/admin/controllers/AdminController.php
     - modules/admin/database
     - - modules/admin/database/migrations
     - - - modules/admin/database/migrations/Create_A_Table_Migrtion.php
     - modules/admin/models
     - - modules/admin/models/statistics.php
     - modules/admin/views
     - - modules/admin/views/dashboard.blade.php
     - modules/admin/web.php
    

## Installation

This version has been tested with Laravel 5.4 only, however other versions will be tested in the future.

```bash
$ composer require neilherbertuk/laravel-modules:dev-master
```

Once installed, you need to register the service provider in your `config/app.php`

```php
        neilherbertuk\modules\ModuleServiceProvider::class,
```

### Configuration

Publish the package's config file. 

```bash
$ php artisan vendor:publish --provider="neilherbertuk\modules\ModuleServiceProvider" --tag=config
```

This will create a `config/modules.php` file in your app that you can modify to set your configuration.

The package can be configured to work in several ways. By default the package will autoload modules under the `app\Modules` folder.

In my opinion (correct me if I am wrong) auto-loading is great in development, but not recommended in production due to the expensive nature of finding each available module.

**Enable Autoload**
```dotenv
MODULES_AUTOLOAD=true
```
**Disable Autoload**
```dotenv
MODULES_AUTOLOAD=true
```

### Usage

To be written.

### TODO
 - [ ] Complete Documentation - Usage Section
 - [ ] Create commands to easily make modules and various parts such as controllers and views within a module.
 - [ ] Create example project
 - [ ] Test support for other versions of Laravel 5
 - [ ] Create Unit Tests

## Bugs
Please report any bugs by opening an issue on [github](https://github.com/neilherbertuk/modules/issues).

## Security Issues
Please email any security issues directly to neil@ea3.co.uk.
