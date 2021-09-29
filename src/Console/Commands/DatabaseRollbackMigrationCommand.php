<?php


namespace Boot\Console\Commands;



use Symfony\Component\Console\Input\InputOption;

class DatabaseRollbackMigrationCommand extends DatabaseMigration
{

    protected $name = 'migrate:rollback';
    protected $help = 'Rollback Database Migration Command';
    protected $description = 'Rollback Previous Database Migration';


    public function handler()
    {
        $this->migrator = $this->migrator();

        $this->migrator->usingConnection($this->option('database'), function ()  {
            $this->migrator->setOutput($this->output)
                ->rollback(database_path('migrations'));
        });
        return 0;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function options()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default'],

            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production'],

            ['path', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The path(s) to the migrations files to be executed'],

            ['realpath', null, InputOption::VALUE_NONE, 'Indicate any provided migration file paths are pre-resolved absolute paths'],

            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run'],

            ['step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted'],
        ];
    }

}
