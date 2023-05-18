<?php

namespace Comes\SimpleAuthenticator\Commands;

use Comes\SimpleAuthenticator\SimpleAuthenticator;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SimpleAuthenticatorCommand extends Command
{
    public $signature = 'mfa:getotp {app}';

    public $description = 'generate OTP for the given app';

    public function handle(SimpleAuthenticator $authenticator): int
    {
        $app = $this->argument('app');

        // get the secret from config file
        $secret = config('simpleauthenticator.secrets.'.$app);

        if (!$secret) {
            // if secret not found, throw exception
            $this->error("Secret for {$app} not found in config file");
            if (!$this->confirm('Calculate anyway?'))
            {
                return self::FAILURE;
            }
            $secret = $app;
        }

        // generate otp
        try {
            $otp = $authenticator->generateOTP($secret);
        } catch (\Exception $e) {
            $this->error('Error generating OTP');

            return self::FAILURE;
        }

        // return otp to console
        $this->output->writeln("<info>Your OTP is:</info> {$otp->getOTP()}");
        $this->output->writeln("<info>Valid until:</info> {$otp->getValidUntil()->toDateTimeString()}");
        // output current time
        $this->output->writeln("<info>Current time:</info> " . Carbon::now()->toDateTimeString());

        return self::SUCCESS;
    }
}
