<?php

namespace neilherbertuk\modules\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use neilherbertuk\modules\Traits\MakeController;
use neilherbertuk\modules\Traits\MakeModule;
use neilherbertuk\modules\Traits\MakeRoutes;

class MakeModuleCommand extends Command
{

    use MakeModule, MakeController, MakeRoutes;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module
                    {module? : Name of module}
                    {filename? : Name of file to create}
                    {--create : Creates scaffold for new module}
                    {--controller : Creates a controller}' .
//                    {--migration : Creates a migration}
//                    {--model : Creates a model}
                    '{--webroute : Creates a web route file}
                    {--apiroute : Creates an API route file}
                    {--plain}';

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
        if ($this->isModuleNameGiven($module)) {

            $this->info('Module Name: ' . $module);

            // Create Module
            if ($this->option('create')) {
                $this->createModule($module);
                return;
            }

            // Create Individual Module Files

            // Create Web Routes File
            if ($this->option('webroute')) {
                // Create web.php routes file
                $this->createWebRoutesFile($module);
                return;
            }

            // Create API Routes File
            if ($this->option('apiroute')) {
                // Create api.php routes file
                $this->createApiRoutesFile($module);
                return;
            }

            // Has a File Name been given?
            if($this->isFileNameGiven($filename)){

                // Create Controller
                if($this->option('controller')) {
                    return $this->createController($module, $filename);
                }
            }

        }

        // Show Usage
        return $this->showUsage();
    }

    /**
     *
     */
    protected function showUsage()
    {
        $this->info($this->getDescription());
        $this->warn('Usage: ');
        $this->line('   make:module ModuleName [--] [FileName]');
        $this->line('');
        $this->warn('Arguments:');
        $this->line('   ModuleName - The name of the module to perform command on');
        $this->line('   FileName - Required for some options - The file name to use if required by an option');
        $this->line('');
        $this->warn('Options:');
        $this->info('   --create                - Creates the folder structure and web routes file for a module');
        $this->info('   --controller [FileName] [--plain] - Creates a controller for the module given - add'.
         ' --plain to create an empty controller.');
//        $this->info('   --migration [FileName]  - Creates a migration for the module given');
//        $this->info('   --model [FileName]      - Creates a model for the module given');
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
}