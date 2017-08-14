<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Module Autoload
    |--------------------------------------------------------------------------
    |
    | This option specifies if modules are auto loaded. If false, modules will
    | be loaded from the enabled array below.
    | Can be set within environment file as MODULES_AUTOLOAD
    |
    | Default: true
    |
    */

    'autoload' => env('MODULES_AUTOLOAD', true),

    /*
    |--------------------------------------------------------------------------
    | Enabled Modules
    |--------------------------------------------------------------------------
    |
    | A list of modules to be loaded if autoload is false.
    |
    | Default: array
    |
    */
    'enabled' => [],

    /*
    |--------------------------------------------------------------------------
    | Disabled Modules
    |--------------------------------------------------------------------------
    |
    | A list of modules to "disable" or not load when autoloading enabled.
    |
    | Default: array
    |
    */
    'disabled' => []

];