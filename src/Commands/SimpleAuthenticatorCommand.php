<?php

namespace Comes\SimpleAuthenticator\Commands;

use Comes\SimpleAuthenticator\SimpleAuthenticator;
use Illuminate\Console\Command;

class SimpleAuthenticatorCommand extends Command
{
    public $signature = 'mfa:getotp {app}';

    public $description = 'generate OTP for the given app';

    public function handle(): int
    {
        $app = $this->argument('app');

        // get the secret from config file
        $secret = config('simpleauthenticator.secrets.' . $app);

        throw_unless($secret, new \Exception("Secret not found for {$app}"));

        $authenticator = new SimpleAuthenticator($secret);

        // generate otp
        $otp = $authenticator->generateOTP();

        // return otp to console
        $this->info("Your OTP is: '{$otp}'");

        return self::SUCCESS;
    }
}
