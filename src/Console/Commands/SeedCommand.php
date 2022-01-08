<?php


namespace Boot\Console\Commands;



use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

class SeedCommand extends DatabaseMigration
{
    protected $name = 'db:seed';
    protected $help = 'Seed the database with records';
    protected $description = 'Seed the database with records';


    public function handler()
    {

        $class = $this->option('class');
       // $capsule = $this->app()->resolve(Capsule::class);
        //$capsule->getConnection();

        $seeder = $this->resolve("\\Database\\Seeders\\$class");

        $seeder->run();

       //$this->resolve();
    }

    /**
     * Resolve an instance of the given seeder class.
     *
     * @param  string  $class
     * @return \Illuminate\Database\Seeder
     */
    protected function resolve($class)
    {
        return new $class;
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function options(): array
    {
        return [
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', 'DatabaseSeeder'],

            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed'],

            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
        ];
    }
}
