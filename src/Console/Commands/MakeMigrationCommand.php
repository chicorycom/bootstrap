<?php

namespace Boot\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Database\Migrations\MigrationCreator;
use Symfony\Component\Console\Input\InputOption;

class MakeMigrationCommand extends MakeScaffoldCommand
{


    protected $name = 'make:migration';
    protected $help = 'Create a new migration file';
    protected $description = 'Make or scaffolded new migration for our database';


    protected function arguments()
    {
        return [
            'name' => $this->require('Add A Migration Name'),
        ];
    }


    protected function options()
    {
        return [
            ['create', null, InputOption::VALUE_REQUIRED, 'How many times should the message be printed?', false],
            ['table', null, InputOption::VALUE_REQUIRED, 'How many times should the message be printed?', 0],
            ['path', null, InputOption::VALUE_REQUIRED, 'How many times should the message be printed?', null],
        ];
    }



    public function handler()
    {
        //$name = $this->input->getArgument('name');
        $name = Str::snake(trim($this->input->getArgument('name')));

        $table = $this->input->getOption('table');

        $create = $this->input->getOption('create') ?: false;


        if (! $table && is_string($create)) {
            $table = $create;

            $create = true;
        }


        // Next, we will attempt to guess the table name if this the migration has
        // "create" in the name. This will allow us to provide a convenient way
        // of creating migrations that create new tables for the application.
        if (! $table) {
            [$create, $table] = TableGuesser::guess($name);
        }


        // Now we are ready to write the migration out to disk. Once we've written
        // the migration out, we will dump-autoload for the entire framework to
        // make sure that the migrations are registered by the class loaders.
        $this->writeMigration($name, $table, $create);
    }

    /**
     * Write the migration file to disk.
     *
     * @param string $name
     * @param string $table
     * @param bool $create
     * @throws \Exception
     */
    protected function writeMigration(string $name, string $table, bool $create)
    {
        try{

            $path = "{$this->stub('stub')}";
            $creator = new MigrationCreator($this->files, $path);
            $file = $creator->create(
                $name, $this->getMigrationPath(), $table, $create
            );
            $this->info("Created Migration: {$file}");
        }catch (\Exception $e){
            $this->error("Error creation migration file!!!!");
        }
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath(): string
    {
        if (! is_null($targetPath = $this->input->getOption('path'))) {
            return ! $this->usingRealPath()
                ? base_path(). DIRECTORY_SEPARATOR .$targetPath
                : $targetPath;
        }

        return database_path('migrations');
    }
}
