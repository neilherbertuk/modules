<?php

namespace neilherbertuk\modules;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use neilherbertuk\modules\Console\MakeModuleCommand;

/**
 * Class ModuleServiceProvider
 * @package neilherbertuk\modules
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
    }

    /**
     *
     */
    public function boot()
    {
        $this->bootInConsole();

        $this->bindClosuresToIOC();

        $this->loadModules();
    }

    /**
     *
     */
    protected function bootInConsole()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {

            // Publish configuration file
            $this->publishes([
                __DIR__ . '/Config/modules.php' => config_path('modules.php'),
            ], "config");

            // Create app/Modules directory
            if (!file_exists(base_path() . "/app/Modules/")) {
                mkdir(base_path() . "/app/Modules/", 0755, true);
            }

            $this->registerCommands();
        }
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->registerModuleMakeCommand();
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerModuleMakeCommand()
    {
        $this->commands([
            MakeModuleCommand::class
        ]);
    }

    /**
     *
     */
    protected function bindClosuresToIOC()
    {
        // Bind a closure to the IOC container which gets called from module's routes files and returns the module name
        $this->bindGetModuleNameClosureToIOC();

        // Bind a closure to the IOC container which gets called from module's routes files and returns the module controller path
        $this->bindGetControllerPathClosureToIOC();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getEnabledModules()
    {
        // Get what modules to load from config or directory
        if (config("modules.autoload")) {
            $modules = $this->getDirectories(base_path() . "/app/Modules/");

        } else {
            $modules = collect(config("modules.enabled"));
        }
        return $modules;
    }

    /**
     * @param $directory
     * @return \Illuminate\Support\Collection
     */
    protected function getDirectories($directory)
    {
        $disabledModules = collect(config('modules.disabled'));
        $directories = collect(scandir($directory))
            ->reject(function($folder) use ($directory, $disabledModules) {
                return !is_dir($directory . DIRECTORY_SEPARATOR . $folder)
                    || $folder == "."
                    || $folder == ".."
                    || $this->isModuleDisabled($folder, $disabledModules);
            });

        return $directories;
    }

    /**
     * @param $moduleToLoad
     * @param $disabledModulesList
     * @return mixed
     */
    protected function isModuleDisabled($moduleToLoad, $disabledModulesList)
    {
        return $disabledModulesList->contains($moduleToLoad);
    }

    /**
     *
     */
    protected function loadModules()
    {
        $modules = $this->getEnabledModules();

        // Load each module
        $modules->each(function($module) {

            $this->loadModuleProviders($module);

            $this->loadModuleRoutes($module);

            $this->loadModuleViews($module);

            $this->loadModuleTranslations($module);

            if ($this->app->runningInConsole()) {
                $this->loadModuleMigrations($module);
            }

        });
    }

    /**
     * @param $module
     */
    protected function loadModuleRoutes($module)
    {
        $this->loadModuleWebRoutes($module);

        $this->loadModuleAPIRoutes($module);

    }

    /**
     * @param $module
     */
    protected function loadModuleWebRoutes($module)
    {
        if (file_exists(base_path('app/Modules/' . $module . '/web.php'))) {
            $this->loadRoutesFrom(base_path('app/Modules/' . $module . '/web.php'));
        }
    }

    /**
     * @param $module
     */
    protected function loadModuleAPIRoutes($module)
    {
        if (file_exists(base_path('app/Modules/' . $module . '/api.php'))) {
            $this->loadRoutesFrom(base_path('app/Modules/' . $module . '/api.php'));
        }
    }

    /**
     * @param $module
     */
    protected function loadModuleViews($module)
    {
        if (is_dir(base_path('app/Modules/' . $module . '/Views'))) {
            $this->loadViewsFrom(base_path('app/Modules/' . $module . '/Views'), strtolower($module));
        }
    }

    /**
     * @param $module
     */
    protected function loadModuleMigrations($module)
    {
        if (is_dir(base_path('app/Modules/' . $module . '/Database/Migrations'))) {
            /** @scrutinizer ignore-call */
            $this->loadMigrationsFrom(base_path('app/Modules/' . $module . '/Database/Migrations'), $module);
        }
    }

    /**
     * @param $module
     */
    protected function loadModuleProviders($module)
    {
        if (is_dir(base_path('app/Modules/' . $module . '/Providers'))) {
            $serviceProviderStartPos = strlen(base_path('app/Modules/' . $module . '/Providers/'));
            $files = glob(base_path('app/Modules/' . $module . '/Providers/*.php'));
            foreach ($files as $file) {
                $this->app->register($this->app->getNamespace() . "Modules\\$module\Providers\\" . substr($file, $serviceProviderStartPos, -4));
            }
        }
    }


    /**
     * @param $module
     */
    protected function loadModuleTranslations($module)
    {
        if (is_dir(base_path('app/Modules/' . $module . '/Lang'))) {
            $this->loadTranslationsFrom(base_path('app/Modules/' . $module . '/Lang'), $module);
        }
    }

    /**
     *
     */
    protected function bindGetModuleNameClosureToIOC()
    {
        $this->app->bind('Module::getNameLowerCase', function($app, $parameters) {
            return strtolower(substr($parameters['path'], strrpos($parameters['path'], "/") + 1));
        });

        $this->app->bind('Module::getName', function($app, $parameters) {
            return substr($parameters['path'], strrpos($parameters['path'], "/") + 1);
        });
    }

    /**
     *
     */
    protected function bindGetControllerPathClosureToIOC()
    {
        $this->app->bind('Module::getControllerPath', function($app, $parameters) {
            return $this->app->getNamespace() . 'Modules\\' . substr($parameters['path'], strrpos($parameters['path'], "/") + 1) . '\Controllers';
        });
    }
}