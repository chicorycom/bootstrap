<?php


namespace Boot\Console\Commands;


class StorageLinkCommand extends MakeScaffoldCommand
{
    protected $name = 'storage:link';
    protected $help = 'Generate softlink storage';
    protected $description = 'Scaffold softlink storage';

    protected function arguments()
    {
        return [
            //'name' => $this->require('Scaffold Softlink Storage'),
        ];
    }

    public function handler()
    {


        $file = 'storage';
        $targetFolder = storage_path('app/public');
        $linkFolder = public_path($file);
        if (!$this->files->exists($targetFolder)) {
            $this->files->makeDirectory($targetFolder, 0777, true);
            //mkdir($targetFolder, 0777, true);
        }

        if ($this->files->exists($linkFolder)) {
            return $this->error("{$file} already exists!");
        }

        $status = symlink($targetFolder,$linkFolder);

        return $this->info("Successfully Generated {$file}! (status: {$status})");
    }
}