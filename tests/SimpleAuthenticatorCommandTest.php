<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    // Mock the time to a fixed timestamp for predictable OTP generation
    Carbon::setTestNow(Carbon::parse('2023-01-01 00:00:00'));
});

it('generates OTP for the given app', function () {
    // Set up the test data
    $app = 'test_app';
    $secret = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    // Set the configuration value for the app secret
    Config::set("simpleauthenticator.secrets.$app", $secret);

    // Execute the command
    $this->artisan('mfa:getotp', ['app' => $app])
        ->expectsOutput('Your OTP is: 900235')
        ->expectsOutput('Valid until: 2023-01-01 00:00:30')
        ->expectsOutput('Current time: 2023-01-01 00:00:00')
        ->assertExitCode(0);
})->group('command');

it('handles secret not found in config file', function () {
    // Set up the test data
    $app = 'nonexistent_app';

    // Execute the command
    $this->artisan('mfa:getotp', ['app' => $app])
        ->expectsOutput("Secret for {$app} not found in config file")
        ->expectsConfirmation('Calculate anyway?', 'no');
})->group('command');

it('handles exception during OTP generation', function () {
    // Set up the test data
    $app = 'test_app';

    // Execute the command
    $this->artisan('mfa:getotp', ['app' => $app])
        ->expectsConfirmation('Calculate anyway?', 'yes')
        ->expectsOutput('Error generating OTP')
        ->assertExitCode(1);
})->group('command');
