<?php


namespace Boot\Providers;



use Boot\Support\View;
use Illuminate\Support\Str;
use Jenssegers\Blade\Blade;
use Slim\Psr7\Factory\ResponseFactory;
use Boot\Foundation\Providers\BladeServiceProvider as ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{


    public function directives($blade)
    {
       // dd($this->app->resolve('csrf'));

       // dd($blade);
        $blade->directive('csrf', function()  {
            $token = $this->app->resolve('csrf');
            $stub = "<input type='hidden' name='{replace}' value='{replace}' />";

            $csrf_value_input = Str::of($stub)->replaceArray('{replace}', [
                $token->getTokenValueKey(),
                $token->getTokenValue()
            ]);

            $csrf_name_input = Str::of($stub)->replaceArray('{replace}', [
                $token->getTokenNameKey(),
                $token->getTokenName()
            ]);

            $stub = '"{replace}{replace}"';

            $expression = Str::of($stub)->replaceArray('{replace}', [
                $csrf_value_input,
                $csrf_name_input
            ]);

            return $expression;
        });
        /*
        $blade->directive('token', function() use($blade) {
            $token = $this->app->resolve('csrf');
            return json_encode([
                $token->getTokenNameKey() =>  $token->getTokenName(),
                $token->getTokenValueKey() => $token->getTokenValue()
            ]);
        });
        */


        // Add custom blade directives
    }


    public function register()
    {
        $this->app->bind(
            View::class,
            fn (Blade $blade, ResponseFactory $factory) => new View($blade, $factory)
        );
    }

    public function boot()
    {

    }
}