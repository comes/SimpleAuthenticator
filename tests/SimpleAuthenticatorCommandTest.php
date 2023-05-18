<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    // Mock the time to a fixed timestamp for predictable OTP generation
    Carbon::setTestNow(Carbon::parse('2023-01-01 00:00:12'));
});

it('generates OTP for the given app', function () {
    // Set up the test data
    $app = 'test_app';
    $secret = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    // Set the configuration value for the app secret
    Config::set("simpleauthenticator.secrets.$app", $secret);

    // Execute the command
    $this->artisan('otp', ['app' => $app])
        ->expectsOutput('One Time Password: 900235')
        ->expectsOutput('Valid until: 2023-01-01 00:00:30')
        ->expectsOutput('Current time: 2023-01-01 00:00:12')
        ->assertExitCode(0);
})->group('command');

it('handles secret not found in config file', function () {
    // Set up the test data
    $app = 'nonexistent_app';

    // Execute the command
    $this->artisan('otp', ['app' => $app])
        ->expectsOutput("Secret for {$app} not found in config file")
        ->expectsConfirmation('Calculate anyway?', 'no');
})->group('command');

it('handles exception during one time password generation', function () {
    // Set up the test data
    $app = 'invalid_app';

    // Execute the command
    $this->artisan('otp', ['app' => $app])
        ->expectsConfirmation('Calculate anyway?', 'yes')
        ->expectsOutput('Invalid secret for one-time password generation')
        ->assertExitCode(1);
})->group('command');
