<?php

namespace Boot\Console\Commands;

use Illuminate\Support\Str;
use Minishlink\WebPush\VAPID;

class VapidKeysGenerateCommand extends MakeScaffoldCommand
{
    protected $name = 'webpush:vapid';
    protected $help = 'Generate VAPID keys.';
    protected $description = 'Generate VAPID keys.';

    protected function arguments()
    {

        return [
            //'--show' => $this->optional('Display the keys instead of modifying files'),
            //'--force' => $this->optional('Force the operation to run when in production')
        ];
    }



    public function handler()
    {
        $keys = VAPID::createVapidKeys();

        //$this->input->getArgument('show');
       /* $this->info($this->input);

        if ($this->input->getOption('show')) {
            $this->comment('VAPID_PUBLIC_KEY='.$keys['publicKey']);
            $this->comment('VAPID_PRIVATE_KEY='.$keys['privateKey']);

            return;
        }*/

        if (! $this->setKeysInEnvironmentFile($keys)) {
            return;
        }

        $this->info("VAPID keys set successfully.");
    }

    /**
     * Set the keys in the environment file.
     *
     * @param  array $keys
     * @return bool
     */
    protected function setKeysInEnvironmentFile($keys): bool
    {
        $currentKeys = config('webpush.vapid');
        if (strlen($currentKeys['public_key']) !== 0) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($keys);

        return true;
    }

    protected function writeNewEnvironmentFileWith($keys){
        $contents = file_get_contents(base_path('.env'));
        if (! Str::contains($contents, 'VAPID_PUBLIC_KEY')) {
            $contents .= PHP_EOL.'VAPID_PUBLIC_KEY=';
        }

        if (! Str::contains($contents, 'VAPID_PRIVATE_KEY')) {
            $contents .= PHP_EOL.'VAPID_PRIVATE_KEY=';
        }

        $contents = preg_replace(
            [
                $this->keyReplacementPattern('VAPID_PUBLIC_KEY'),
                $this->keyReplacementPattern('VAPID_PRIVATE_KEY'),
            ],
            [
                'VAPID_PUBLIC_KEY='.$keys['publicKey'],
                'VAPID_PRIVATE_KEY='.$keys['privateKey'],
            ],
            $contents
        );

        file_put_contents(base_path('.env'), $contents);
    }


    /**
     * Get a regex pattern that will match env $keyName with any key.
     *
     * @param string $keyName
     * @return string
     */
    protected function keyReplacementPattern(string $keyName): string
    {
        $key = config('webpush.vapid');

        if ($keyName === 'VAPID_PUBLIC_KEY') {
            $key = $key['public_key'];
        } else {
            $key = $key['private_key'];
        }

        $escaped = preg_quote('='.$key, '/');

        return "/^{$keyName}{$escaped}/m";
    }
}
