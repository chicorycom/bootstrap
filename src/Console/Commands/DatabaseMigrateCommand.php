<?php


namespace Boot\Console\Commands;


class DatabaseMigrateCommand extends Command
{
    protected $name = 'migration:migrate';
    protected $help = 'migrate';
    protected $description = 'Migration migrations to database';

    public function handler()
    {
        $status = shell_exec("./vendor/bin/phinx migrate");

        $this->info($status);
        $this->info("Successful when no error shown above");
    }
}
