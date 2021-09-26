<?php


namespace Boot\Console\Commands;


class DatabaseRollbackMigrationCommand extends Command
{
    protected $name = 'migration:rollback';
    protected $help = 'Rollback Database Migration Command';
    protected $description = 'Rollback Previous Database Migration';

    public function handler()
    {
        $command = "./vendor/bin/phinx rollback";
        $status = shell_exec($command);
        $this->info($status);
        $this->info("Successful (If this message is the only message, Errors show above)");
    }
}
