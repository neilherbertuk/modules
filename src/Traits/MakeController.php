<?php

namespace neilherbertuk\modules\Traits;

use Illuminate\Support\Facades\App;

trait MakeController{

    /**
     * @param $module
     * @param $filename
     */
    protected function createController($module, $filename)
    {
        $this->info('Creating a new Controller');
        $this->info('Creating ' . $module . ':' . $filename . ' Controller');
        $this->createControllersFolder($module);
        if (!file_exists(base_path() . "/app/Modules/" . $module . "/Controllers/" . $filename . ".php")) {
            file_put_contents(
                base_path() . "/app/Modules/" . $module . "/Controllers/" . $filename . ".php",
                $this->compileControllerStub($module, $filename)
            );
            (file_exists(base_path() . "/app/Modules/" . $module . "/Controllers/" . $filename . ".php") ? $this->info('Done'):$this->error('An error occurred'));
            return;
        }
        $this->error('Controller already exists');
        return;
    }

    /**
     * @param $module
     */
    protected function createControllersFolder($module)
    {
        $this->createFolderInModule($module, "Controllers");
    }

    /**
     * @param $moduleName
     * @param $className
     * @return mixed
     */
    protected function compileControllerStub($moduleName, $className)
    {
        $stub = __DIR__ . '/../Stubs/controller.stub';

        if ($this->option('plain')) {
            $stub = __DIR__ . '/../Stubs/controller.plain.stub';
        }

        return str_replace(
            ['DummyNamespace', 'DummyClass', 'DummyRootNamespaceHttp'],
            [App::getNamespace() . 'Modules\\' . $moduleName . '\Controllers', $className, App::getNamespace() .'Http'],
            file_get_contents($stub)
        );
    }

}