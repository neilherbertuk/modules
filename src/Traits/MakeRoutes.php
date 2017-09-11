<?php

namespace neilherbertuk\modules\Traits;

use Illuminate\Support\Facades\App;

trait MakeRoutes{
    /**
     * @param $module
     */
    protected function createWebRoutesFile($module)
    {
        $this->createRoutesFile($module, "web");
    }

    /**
     * @param $module
     */
    protected function createApiRoutesFile($module)
    {
        $this->createRoutesFile($module, "api");
    }

    /**
     * @param $module
     */
    protected function createRoutesFile($module, $type)
    {
        if (!file_exists(base_path() . "/app/Modules/" . $module . "/" . $type . ".php")) {
            $this->info("Creating $type routes file");
            file_put_contents(
                base_path() . "/app/Modules/" . $module . "/" . $type . ".php",
                $this->compileRoutesStub($module, $type)
            );
        }
    }

    /**
     * @param $module
     * @param $type
     * @return mixed
     */
    protected function compileRoutesStub($module, $type)
    {
        return str_replace(
            ['{{moduleName}}', '{{routeType}}'],
            [$module, $type],
            file_get_contents(__DIR__ . '/../Stubs/route.stub')
        );
    }
}
