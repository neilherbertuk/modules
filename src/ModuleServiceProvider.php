<?php

namespace neilherbertuk\Modules;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class ModuleServiceProvider
 * @package neilherbertuk\Modules
 */
class ModuleServiceProvider extends ServiceProvider
{

    /**
     *
     */
    public function boot()
    {

        // Bind a closure to the IOC container which gets called from module's routes files and returns the module name
        $this->bindGetModuleNameClosureToIOC();

        // Get what modules to load from config or directory
        if (config("module.autoload")) {
            $modules = $this->getDirectories(base_path() . "/app/Modules/");

        } else {
            $modules = collect(config("module.enabled"));
        }

        // Load each module
        $modules->each(function ($module) {

            $this->loadModuleProviders($module);

            $this->loadModuleRoutes($module);

            $this->loadModuleViews($module);

            if ($this->app->runningInConsole()) {

                $this->loadModuleMigrations($module);

            }

        });
    }

    /**
     *
     */
    public function register()
    {
    }

    /**
     * @param $directory
     * @return \Illuminate\Support\Collection
     */
    protected function getDirectories($directory)
    {
        $disabledModules = collect(config('module.disabled'));
        $directories = collect(scandir($directory))->reject(function ($folder) use ($directory, $disabledModules) {
            return !is_dir($directory . DIRECTORY_SEPARATOR . $folder) or $folder == "." or $folder == ".." or $this->isModuleDisabled($folder, $disabledModules);
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
            //include base_path('app/Modules/' . $module . '/web.php');
            $this->loadRoutesFrom(base_path('app/Modules/' . $module . '/web.php'));
        }
    }

    /**
     * @param $module
     */
    protected function loadModuleAPIRoutes($module)
    {
        if (file_exists(base_path('app/Modules/' . $module . '/api.php'))) {
            //include base_path('app/Modules/' . $module . '/api.php');
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
                $this->app->register("App\Modules\\$module\Providers\\" . substr($file, $serviceProviderStartPos, -4));
            }
        }
    }

    /**
     *
     */
    protected function bindGetModuleNameClosureToIOC()
    {
        $this->app->bind('Module::getName', function ($app, $parameters) {
            return strtolower(substr($parameters['path'], strrpos($parameters['path'], "/") + 1));
        });
    }
}