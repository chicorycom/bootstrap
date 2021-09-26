<?php


use Boot\Foundation\Events\Dispatcher;
use Boot\Support\RequestInput;
use Boot\Support\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\UploadedFile;


if (!function_exists('view'))
{
    function view($template, $with=[]): Response
    {
       // dd($_SERVER['REQUEST_URI'], explode('/', $_SERVER['REQUEST_URI'])[1]);
        $view = app()->resolve(View::class);
        return $view($template, $with);
    }
}


if (!function_exists('event'))
{
    function event() : Dispatcher
    {
        return app()->resolve('events');
    }
}

if (!function_exists('old'))
{
    function old($key)
    {
        $input = app()->resolve('old_input');

        $field = collect($input)->filter(fn ($value, $field) => $key == $field);

        if (isset($field[$key])) {
            return $field[$key];
        }
    }
}

if (!function_exists('back'))
{
    function back()
    {
        $route = app()->resolve(RequestInput::class);

        $back = $route->getCurrentUri();

        return redirect($back);
    }
}

if (!function_exists('validator'))
{
    function validator(array $input, array $rules, array $messages = [])
    {
        $factory = app()->resolve(\Boot\Foundation\Http\ValidatorFactory::class);

        return $factory->make($input, $rules, $messages);
    }
}


if (!function_exists('session'))
{
    function session($key = false, $value = false)
    {
        $session = app()->resolve(\Boot\Foundation\Http\Session::class);

        if (!$key) {
            return $session;
        }

        if (!$value) {
            return $session->get($key);
        }

        $session->set($key, $value);

        return $session;
    }
}

if(!function_exists('csrf_field'))
{
    function csrf_field(): \Illuminate\Support\Stringable
    {
        $token = app()->resolve('csrf');
        $stub = "<input type='hidden' name='{replace}' value='{replace}' />";


        $csrf_value_input = Str::of($stub)->replaceArray('{replace}', [
            $token->getTokenValueKey(),
            $token->getTokenValue()
        ]);

        $csrf_name_input = Str::of($stub)->replaceArray('{replace}', [
            $token->getTokenNameKey(),
            $token->getTokenName()
        ]);

        $stub = "{replace} \n {replace}";

        $expression = Str::of($stub)->replaceArray('{replace}', [
            $csrf_value_input,
            $csrf_name_input
        ]);

        return $expression;
    }
}

if (!function_exists('asset'))
{
    function asset($path): string
    {
        return env('APP_URL') . "/{$path}";
    }
}


if (!function_exists('redirect'))
{
    function redirect(string $to)
    {
        $redirect = app()->resolve(\Boot\Support\Redirect::class);

        return $redirect($to);
    }
}

if(!function_exists('move')){
    /**
     * @param $directory
     * @param UploadedFile $uploadedFile
     * @param null $basename
     * @return string
     * @throws Exception
     */
    function move($directory, UploadedFile $uploadedFile, $basename=null): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        if(!$basename){
            $basename = md5(bin2hex(random_bytes(8))); // see http://php.net/manual/en/function.random-bytes.php
        }

        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}



if (!function_exists('collect'))
{
    function collect($items): Collection
    {
        return new Collection($items);
    }
}



if (!function_exists('env'))
{
    function env($key, $default = null)
    {
        $value = getenv($key);

        throw_when(!$value and !$default, "{$key} is not a defined .env variable and has not default value");

        return $value or $default;
    }
}

if (!function_exists('base_path'))
{
    function base_path($path = ''): string
    {
        return  dirname( dirname(__DIR__) ) . "/{$path}";
    }
}

if (!function_exists('database_path'))
{
    function database_path($path = ''): string
    {
        return base_path("src/database/{$path}");
    }
}

if (!function_exists('src_path'))
{
    function src_path($path = ''): string
    {
        return base_path("src/{$path}");
    }
}


if (!function_exists('config_path'))
{
    function config_path($path = ''): string
    {
        return base_path("src/config/{$path}");
    }
}

if (!function_exists('storage_path'))
{
    function storage_path($path = ''): string
    {
        return base_path("src/storage/{$path}");
    }
}

if (!function_exists('public_path'))
{
    function public_path($path = '')
    {
        return base_path("public/{$path}");
    }
}

if (!function_exists('resources_path'))
{
    function resources_path($path = ''): string
    {
        return base_path("src/resources/{$path}");
    }
}

if (!function_exists('routes_path'))
{
    function routes_path($path = '')
    {
        return base_path("src/routes/{$path}");
    }
}

if (!function_exists('app_path'))
{
    function app_path($path = ''): string
    {
        return base_path("src/chicorycom/{$path}");
    }
}

if(!function_exists('str_limit')){

    /**
     * @param $string
     * @param int $limit
     * @param string $end
     * @return string
     */
    function  str_limit($string, $limit=20, $end='...'): string
    {
       return Str::limit($string, $limit, $end);
    }
}

if (!function_exists('throw_when'))
{
    function throw_when(bool $fails, string $message, string $exception = Exception::class)
    {
        if (!$fails) return;

        throw new $exception($message);
    }
}

if (! function_exists('class_basename'))
{
    function class_basename($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('config'))
{
    function config($path = null)
    {
        $config = [];
        $folder = scandir(config_path());
        $config_files = array_slice($folder, 2, count($folder));

        foreach ($config_files as $file)
        {
            throw_when(
                Str::after($file, '.') !== 'php',
                'Config files must be .php files'
            );


            data_set($config, Str::before($file, '.php') , require config_path($file));
        }

        return data_get($config, $path);
    }
}

if (! function_exists('data_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed  $target
     * @param  string|array|int|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (! is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (! is_array($target)) {
                    return value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = data_get($item, $key);
                }

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }

        return $target;
    }
}

if (! function_exists('data_set')) {
    /**
     * Set an item on an array or object using dot notation.
     *
     * @param mixed $target
     * @param string|array $key
     * @param mixed $value
     * @param bool $overwrite
     * @return mixed
     */
    function data_set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*') {
            if (!Arr::accessible($target)) {
                $target = [];
            }

            if ($segments) {
                foreach ($target as &$inner) {
                    data_set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (Arr::accessible($target)) {
            if ($segments) {
                if (!Arr::exists($target, $segment)) {
                    $target[$segment] = [];
                }

                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || !Arr::exists($target, $segment)) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if (!isset($target->{$segment})) {
                    $target->{$segment} = [];
                }

                data_set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || !isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];

            if ($segments) {
                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }

        return $target;
    }
}




