<?php


namespace Boot\Foundation;


class App extends \Slim\App
{

    /**
     * The Laravel framework version.
     *
     * @var string
     */
    public const VERSION = '2.1.7';


    /**
     * The base path for the Laravel installation.
     *
     * @var string
     */
    protected $basePath;


    public function bootedViaConsole()
    {
        return $this->has('bootedViaConsole')
            ? $this->resolve('bootedViaConsole')
            : false;
    }

    public function bootedViaHttpRequest()
    {
        return $this->has('bootedViaHttp')
            ? $this->resolve('bootedViaHttp')
            : false;
    }

    public function call(...$parameters)
    {
        return $this->getContainer()->call(...$parameters);
    }

    public function has(...$parameters)
    {
        return $this->getContainer()->has(...$parameters);
    }

    public function bind(...$parameters)
    {
        return $this->getContainer()->set(...$parameters);
    }

    public function make(...$parameters)
    {
        return $this->getContainer()->make(...$parameters);
    }

    public function resolve(...$parameters)
    {
        return $this->getContainer()->get(...$parameters);
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setPath($basePath): App
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->basePath;
    }
}