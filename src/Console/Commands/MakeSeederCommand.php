<?php


namespace Boot\Console\Commands;


class MakeSeederCommand extends MakeScaffoldCommand
{
    protected $name = 'make:seeder';
    protected $help = 'Make a Seeder Scaffold';
    protected $description = 'Generate a database seeder scaffold';

    protected function arguments()
    {
        return [
            'name' => $this->require('Name of Scaffolded Seeder Class')
        ];
    }

    public function handler()
    {

        $file = $this->scaffold(
            $this->stub('file'),
            $this->stub('replace.file')
        );

        $content = $this->scaffold(
            $this->stub('content'),
            $this->stub('replace.content')
        );

        $path = "{$this->stub('make_path')}/{$file}";

        $exists = $this->files->exists($path);

        if ($exists) {
            return $this->error("{$file} already exists!");
        }

        $status = $this->files->put($path, $content);

        $this->info("Successfully Generated {$file}! (status: {$status})");
    }
}
