<?php


namespace Boot\Foundation\Console;



use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{


    public function __construct($version)
    {
        parent::__construct('Chicoricom Framework', $version);


    }
}