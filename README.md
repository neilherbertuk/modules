Laravel Modules
===============
v0.0.1

A package to add modules to a laravel application. This package allows you to separate out code for parts of your application into their own dedicated "modules" or folders, allowing all code related to a specific section or function of your application to be stored in one place.

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

This version has been tested with Laravel 5.4 only.

```bash
$ composer require neilherbertuk/modules
```

Once installed, you need to register the `neilherbertuk\modules\ModulesServiceProvider` service provider in your `config/app.php`

### Usage

To be written.

## Bugs and Security Issues
Please report any bugs by opening an issue on github.
Please email any security issues directly to neil@ea3.co.uk
