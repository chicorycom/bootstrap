<?php


namespace Boot\Console\Commands;


use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Events\Dispatcher;

class DatabaseMigration extends Command
{


    /**
     * @var DatabaseMigrationRepository
     */
    protected DatabaseMigrationRepository $repository;
    /**
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;
    /**
     * @var Migrator
     */
    protected Migrator $migrator;

    /**
     * @return Migrator
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function migrator(): Migrator
    {
        $capsule = $this->app()->resolve(Capsule::class);
        $this->dispatcher = new Dispatcher(new Container);
        $capsule->setEventDispatcher($this->dispatcher);
        $container = Container::getInstance();
        $databaseMigrationRepository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
        $this->repository = $databaseMigrationRepository;
        $container->instance(MigrationRepositoryInterface::class, $databaseMigrationRepository);
        $container->instance(ConnectionResolverInterface::class, $capsule->getDatabaseManager());
        /** @var Migrator $migrator */
        return $container->make(Migrator::class);
    }


}