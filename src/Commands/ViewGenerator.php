<?php

namespace Singularity\Crud\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class ViewGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:views {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Crud views';

    private $current_stub;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return [
            'index.blade.php' => __DIR__ . '/../../stubs/bootstrap/index.stub',
            'create.blade.php' => __DIR__ . '/../../stubs/bootstrap/create.stub',
            'edit.blade.php' => __DIR__ . '/../../stubs/bootstrap/edit.stub',
            'view.blade.php' => __DIR__ . '/../../stubs/bootstrap/view.stub'
        ];
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return resource_path('views/' . $this->getDirectoryName($name));
    }

    protected function getDirectoryName($name)
    {
        return  Str::plural(strtolower(Str::kebab($name)));
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function generateView($name)
    {
        $replace = [
            'resourceVar' => Str::camel($name),
            'resourcePlural' => Str::plural(Str::camel($name)),
            'resourceViewPath' => Str::plural(Str::kebab($name)),
        ];
        return str_replace(
            array_keys($replace),
            array_values($replace),
            $this->files->get($this->current_stub)
        );
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
        $this->comment('Building new crudable views.');

        $path = $this->getPath($this->getNameInput());
        if ($this->alreadyExists($this->getNameInput())) {
            $this->error('View already exist!');
            return false;
        }

        foreach ($this->getStub() as $name => $stub) {
            $this->current_stub = $stub;
            $this->makeDirectory($path . '/' . $name);
            $this->files->put($path . '/' . $name, $this->generateView($this->getNameInput()));
        }
        $this->info('Views created successfully.');
    }
}
