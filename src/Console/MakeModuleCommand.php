<?php

namespace neilherbertuk\modules\Console;

use Illuminate\Console\Command;

class MakeModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module
                    {module? : Name of module}
                    {filename? : Name of file to create}
                    {--create : Creates scaffold for new module}'.
//                    {--view : Creates a view}
//                    {--controller : Creates a controller}
//                    {--migration : Creates a migration}
//                    {--model : Creates a model}
                    '{--webroute : Creates a web route file}
                    {--apiroute : Creates an API route file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold for laravel modules';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get user input - Module Name and Filename
        $module = $this->argument('module');
        $filename = $this->argument('filename');

        // Has a Module Name been provided?
        if($this->isModuleNameGiven($module)){

            $this->info('Module Name: '. $module);
            if($this->option('create')){
                $this->info('Creating a new Module');

                // Create all module's folders
                $this->info('Creating folder structure');
                $this->createModuleFolders($module);

                // Create web.php routes file
                $this->createWebRoutesFile($module);
                return;
            }

            // Create Web Routes File
            if($this->option('webroute')){
                $this->info('Creating a web route file for Module');

                // Create web.php routes file
                $this->createWebRoutesFile($module);
                return;
            }

            // Create API Routes File
            if($this->option('apiroute')){
                $this->info('Creating an api route file for Module');

                // Create web.php routes file
                $this->createApiRoutesFile($module);
                return;
            }

            // Has a File Name been given?
//            if($this->isFileNameGiven($filename)){
//
//                if($this->option('view')) {
//                    $this->info('Creating a new View');
//                    $this->createViewsFolder($module);
//                    return;
//                }
//                if($this->option('controller')) {
//                    $this->info('Creating a new Controller');
//                    $this->createControllersFolder($module);
//                    return;
//                }
//                if($this->option('migration')) {
//                    $this->info('Creating a new Migration');
//                    $this->createDatabaseMigrationsFolder($module);
//                    return;
//                }
//                if($this->option('model')) {
//                    $this->info('Creating a new Model');
//                    $this->createModelsFolder($module);
//                    return;
//                }
//            }

        }

        return $this->showUsage();
    }

    /**
     *
     */
    protected function showUsage()
    {
        $this->info($this->getDescription());
        $this->error('These commands currently do not work.');
        $this->warn('Usage: ');
        $this->line('   make:module ModuleName [--] [FileName]');
        $this->line('');
        $this->warn('Arguments:');
        $this->line('   ModuleName - The name of the module to perform command on');
        $this->line('   FileName - Required for some options - The file name to use if required by an option');
        $this->line('');
        $this->warn('Options:');
        $this->info('   --create                - Creates the folder structure and web routes file for a module');
//        $this->info('   --controller [FileName] - Creates a controller for the module given');
//        $this->info('   --migration [FileName]  - Creates a migration for the module given');
//        $this->info('   --model [FileName]      - Creates a model for the module given');
//        $this->info('   --view [FileName]       - Creates a view for the module given');
        $this->info('   --webroute              - Creates a web routes file for the module given');
        $this->info('   --apiroute              - Creates a web routes file for the module given');
    }

    /**
     * @param $module
     * @return bool
     */
    protected function isModuleNameGiven($module)
    {
        return !empty($module);
    }

    /**
     * @param $filename
     * @return bool
     */
    protected function isFileNameGiven($filename)
    {
        return !empty($filename);
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
    protected function createControllersFolder($module)
    {
        $this->createFolderInModule($module, "Controllers");
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
        if (!is_dir(base_path() . "/app/Modules/". $module . "/" . $folder)) {
            // Create directory
            return mkdir(base_path() . "/app/Modules/". $module . "/" . $folder, 0755, true);
        }
        return false;
    }

    protected function compileRoutesStub($module, $type)
    {
        return str_replace(
            ['{{modulename}}', '{{rotueType}}'],
            [$module, $type],
            file_get_contents(__DIR__.'/../Stubs/route.stub')
        );
    }

    /**
     * @param $module
     */
    protected function createRoutesFile($module, $type)
    {
        if (!file_exists(base_path() . "/app/Modules/" . $module . "/". $type .".php")) {
            $this->info('Creating '. $type .' routes file');
            file_put_contents(
                base_path() . "/app/Modules/" . $module . "/". $type .".php",
                $this->compileRoutesStub($module, $type)
            );
        }
    }

    /**
     * @param $module
     */
    protected function createWebRoutesFile($module){
        $this->createRoutesFile($module, "web");
    }

    /**
     * @param $module
     */
    protected function createApiRoutesFile($module){
        $this->createRoutesFile($module, "api");
    }
}