<?php

namespace Singularity\Crud\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class RequestGenerator extends GeneratorCommand
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
    protected $description = 'Generates Crud form request';


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/request.stub';
    }


    // Custom required methods
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\Http\\Requests";
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

        $this->info('Rrequest created successfully.');
    }
}
