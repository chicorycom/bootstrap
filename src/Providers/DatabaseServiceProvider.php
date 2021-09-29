<?php


namespace Boot\Providers;



use Illuminate\Database\Capsule\Manager as Capsule;


class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $options = data_get(config('database.connections'), config('database.default'));

        $capsule = new Capsule;
        $capsule->addConnection($options);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->bind(Capsule::class, fn () => $capsule);
    }

    public function boot()
    {
        //
    }
}