<?php

namespace Singularity\Crud\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class MigrationGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:migration {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Crud migration';


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/migration.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $fileName = $this->getFileName($name);
        return database_path("migrations/$fileName.php");
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getFileName($name)
    {
        return date('Y_m_d') . '_' . time() . '_create_' . Str::plural(Str::snake($name)) . '_table';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getClassName($name)
    {
        return 'Create' . Str::plural($name) . 'Table';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return '';
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
        $replace = [
            'resourceDbView' => Str::plural(Str::snake($name)),
            '{{ class }}' => $name,
        ];
        return str_replace(
            array_keys($replace),
            array_values($replace),
            $this->generateClass($this->getClassName($name))
        );
    }

    protected function generateClass($name)
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceClass($stub, $name);
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
        $this->comment('Building new crud migration.');

        $name = $this->getNameInput();
        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error('Migration already exist!');
            return false;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info('Migration created successfully.');
    }
}
