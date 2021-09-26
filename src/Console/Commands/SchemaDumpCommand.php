<?php

namespace Boot\Console\Commands;

use Illuminate\Database\Migrations\MigrationCreator as MC;
use InvalidArgumentException;

class SchemaDumpCommand extends MakeScaffoldCommand
{
    protected $name = 'schema:dump';
    protected $help = 'Try to use ./vendor/bin/phinx instead';
    protected $description = 'creates migration at migration_folder/schema/schema.env.php';

    protected function arguments()
    {
        return [
           // 'name' => $this->require('Add A Migration Name')
        ];
    }

    public function handler()
    {
       // $name = $this->input->getArgument('name');
        //$path = $path.'/'.date('Y').'/'.date('m');
        $command = "./vendor/bin/phinx schema:dump";

        shell_exec($command);

        $this->info("Successful if no error thrown above");
    }
}
