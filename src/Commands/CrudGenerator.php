<?php

namespace Singularity\Crud\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Storage;

class CrudGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a CRUD funcationalities for a given resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/model.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\Models";
    }

    // Custom required methods
    protected function controllerNameSpace()
    {
        return $this->rootNamespace() . "\Http\\Controllers\\";
    }

    protected function getMigrationClassName($name)
    {
        return "Create" . Str::plural($name) . "Table";
    }

    protected function getRequestClassName($name)
    {
        return $name . "Request";
    }

    protected function getControllerClassName($name)
    {
        return $this->controllerNameSpace() . $name . "Controller";
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment('Building crud views for the resource.');

        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error('Resource already exist!');
            return false;
        }

        // Generate Model
        $this->makeDirectory($path);
        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        // Append resource route to web.php
        $model = str_replace($this->getNamespace($name).'\\', '', $name);
        $resourceName = Str::plural(strtolower(Str::kebab($model)));
        $controllerClass = $this->getControllerClassName($model);

        $this->call('generate:migration', ['name' => $model]);
        $this->call('generate:request', ['name' => $this->getRequestClassName($model)]);
        $this->call('generate:controller', ['name' => $controllerClass]);
        $this->call('generate:views', ['name' => $model]);

        Storage::append('routes/web.php', "Route::resource('$resourceName', $controllerClass::class)");

        $this->info('Resource generated successfully.');
    }
}
