<?php


namespace Boot\Console\Commands;


use Symfony\Component\Console\Input\InputOption;

class DataBaseInstallMigration extends DatabaseMigration
{
    protected $name = 'migrate:install';
    protected $help = 'Create the migration repository';
    protected $description = 'Create the migration repository';




    public function handler()
    {
        $this->migrator = $this->migrator();
        if ( $this->migrator->repositoryExists()) {
                $this->error('The repository already exists');
            return 1;
        }
        $this->repository->setSource($this->input->getOption('database'));

        $this->repository->createRepository();

        $this->info('Migration table created successfully.');
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
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
        ];
    }
}