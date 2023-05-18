<?php

namespace Comes\SimpleAuthenticator\Commands;

use Comes\SimpleAuthenticator\SimpleAuthenticator;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SimpleAuthenticatorCommand extends Command
{
    public $signature = 'otp {app}';

    public $description = 'Generate a One Time Password';

    public function handle(SimpleAuthenticator $authenticator): int
    {
        $app = $this->argument('app');

        // get the secret from config file
        $secret = config('simpleauthenticator.secrets.'.$app);

        if (! $secret) {
            // if secret not found, throw exception
            $this->error("Secret for {$app} not found in config file");
            if (! $this->confirm('Calculate anyway?')) {
                return self::FAILURE;
            }
            $secret = $app;
        }

        // generate otp
        try {
            $oneTimePassword = $authenticator->generate($secret);
        } catch (\Exception $e) {
            $this->error('Failed to generate one time password');

            return self::FAILURE;
        }

        // return otp to console
        $this->output->writeln("<info>One Time Password:</info> {$oneTimePassword->getOneTimePassword()}");
        $this->output->writeln("<info>Valid until:</info> {$oneTimePassword->getValidUntil()->toDateTimeString()}");
        // output current time
        $this->output->writeln('<info>Current time:</info> '.Carbon::now()->toDateTimeString());

        return self::SUCCESS;
    }
}
