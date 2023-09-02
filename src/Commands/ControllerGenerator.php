<?php

namespace Singularity\Crud\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class ControllerGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Crud controller';


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/controller.stub';
    }


    // Custom required methods
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "Http\\Controllers";
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $resourceName = str_replace('Controller', '', str_replace($this->getNamespace($name) . '\\', '', $name));
        $replace = [
            'resourceVar' => Str::camel($resourceName),
            'resourceVarPlural' => Str::plural(Str::camel($resourceName)),
            'resourcePath' => Str::plural(Str::kebab($resourceName)),
            'resourseClass' => $resourceName,
            'ResourceRequest' => Str::ucfirst($resourceName) . 'Request'
        ];
        return str_replace(
            array_keys($replace),
            array_values($replace),
            $this->generateClass($name)
        );
    }

    protected function generateClass($name)
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getPath($this->getNameInput()));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Building new crud controller.');

        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exist!');
            return false;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info('Controller created successfully.');
    }
}
