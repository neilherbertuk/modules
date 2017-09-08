<?php

namespace neilherbertuk\modules\Traits;

use Illuminate\Support\Facades\App;

trait MakeModule
{
    /**
     * @param $module
     */
    protected function createModule($module)
    {
        $this->info('Creating a new Module');

        if (is_dir(base_path('app/Modules/' . $module))) {
            $this->error($module . " already exists. If you really want to create this module, please delete the following folder: " . base_path('app/Modules/' . $module));
            return;
        }

        // Create all module's folders
        $this->info('Creating folder structure');
        $this->createModuleFolders($module);


        // Create routes file

        // API Routes File
        if ($this->option('apiroute')) {
            $this->createApiRoutesFile($module);
        }

        // Web Routes File
        if ($this->option('webroute') || !$this->option('apiroute')) {
            $this->createWebRoutesFile($module);
        }

        $this->info('Done');
    }

    /**
     * @param $module
     */
    protected function createModuleFolders($module)
    {
        $this->createViewsFolder($module);
        $this->createControllersFolder($module);
        $this->createDatabaseMigrationsFolder($module);
        $this->createModelsFolder($module);
    }

    /**
     * @param $module
     */
    protected function createModelsFolder($module)
    {
        $this->createFolderInModule($module, "Models");
    }

    /**
     * @param $module
     */
    protected function createDatabaseMigrationsFolder($module)
    {
        $this->createFolderInModule($module, "Database/Migrations");
    }

    /**
     * @param $module
     */
    protected function createViewsFolder($module)
    {
        $this->createFolderInModule($module, "Views");
    }

    /**
     * @param $module
     * @param $folder
     * @return bool
     */
    protected function createFolderInModule($module, $folder)
    {
        // Does Directory Exist?
        if (!is_dir(base_path() . "/app/Modules/" . $module . "/" . $folder)) {
            // Create directory
            return mkdir(base_path() . "/app/Modules/" . $module . "/" . $folder, 0755, true);
        }
        return false;
    }
}