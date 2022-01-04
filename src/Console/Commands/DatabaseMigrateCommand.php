<?php


namespace Boot\Console\Commands;

use Illuminate\Database\Events\SchemaLoaded;
use Illuminate\Database\SqlServerConnection;
use Symfony\Component\Console\Input\InputOption;

class DatabaseMigrateCommand extends DatabaseMigration
{
    protected $name = 'migrate';
    protected $help = 'migrate';
    protected $description = 'Migration migrations to database';

    protected function options()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],

            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production'],

            ['path', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The path(s) to the migrations files to be executed'],

            ['realpath', null, InputOption::VALUE_NONE, 'Indicate any provided migration file paths are pre-resolved absolute paths'],

            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run'],

            ['step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted'],
        ];
    }

    public function handler()
    {
        $this->migrator = $this->migrator();
        $this->migrator->usingConnection($this->option('database'), function () {
            $this->prepareDatabase();



            // Next, we will check to see if a path option has been defined. If it has
            // we will use the path relative to the root of this installation folder
            // so that migrations may be run for any path within the applications.
            $this->migrator->setOutput($this->output)
                ->run(database_path('migrations'),[
                    'pretend' => $this->option('pretend'),
                    'step' => $this->option('step'),
                ]);

           // $this->loadSchemaState() ;
        });
        return 0;
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        if (! $this->migrator->repositoryExists()) {
                $this->migrator->getRepository()->createRepository();
            return;
        }

        if (! $this->migrator->hasRunAnyMigrations() && ! $this->option('pretend')) {
            $this->loadSchemaState();
        }
    }

    /**
     * Load the schema state to seed the initial database schema structure.
     *
     * @return void
     */
    protected function loadSchemaState()
    {
        $connection = $this->migrator->resolveConnection($this->option('database'));
       // dd(is_file($path = $this->schemaPath($connection)));
        // First, we will make sure that the connection supports schema loading and that
        // the schema file exists before we proceed any further. If not, we will just
        // continue with the standard migration operation as normal without errors.
        if ($connection instanceof SqlServerConnection ||
            ! is_file($path = $this->schemaPath($connection))) {
            return;
        }

        $this->info('Loading stored database schema: '.$path);

        $startTime = microtime(true);

        // Since the schema file will create the "migrations" table and reload it to its
        // proper state, we need to delete it here so we don't get an error that this
        // table already exists when the stored database schema file gets executed.
        $this->migrator->deleteRepository();

        $connection->getSchemaState()->handleOutputUsing(function ($type, $buffer) {
            $this->output->write($buffer);
        })->load($path);

        $runTime = number_format((microtime(true) - $startTime) * 1000, 2);

        // Finally, we will fire an event that this schema has been loaded so developers
        // can perform any post schema load tasks that are necessary in listeners for
        // this event, which may seed the database tables with some necessary data.
        $this->dispatcher->dispatch(
            new SchemaLoaded($connection, $path)
        );

        $this->info('Loaded stored database schema. ('.$runTime.'ms)');
    }

    /**
     * Get the path to the stored schema for the given connection.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @return string
     */
    protected function schemaPath($connection): string
    {
        if (file_exists($path = database_path('schema/'.$connection->getName().'-schema.dump'))) {
            return $path;
        }

        return database_path('schema/'.$connection->getName().'-schema.sql');
    }
}
