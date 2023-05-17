<?php

use Comes\SimpleAuthenticator\SimpleAuthenticator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    // Mock the time to a fixed timestamp for predictable OTP generation
    Carbon::setTestNow(Carbon::parse('2023-01-01 00:00:00'));
});

it('generates OTP correctly', function () {
    $secret = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $authenticator = new SimpleAuthenticator;

    $expectedOTP = '900235';
    $otp = $authenticator->generateOTP($secret);

    expect($otp)->toBe($expectedOTP);
});

it('throws exception for invalid base32 character', function () {
    $secret = 'INVALID_KEY';
    $authenticator = new SimpleAuthenticator;

    expect(function () use ($authenticator, $secret) {
        $authenticator->generateOTP($secret);
    })->toThrow(\RuntimeException::class, 'Invalid base32 character');
});

it('can load secret from config file', function () {
    $secret = config('simpleauthenticator.secrets.sample');
    $authenticator = new SimpleAuthenticator($secret);

    $expectedOTP = '900235';
    $otp = $authenticator->generateOTP($secret);

    expect($otp)->toBe($expectedOTP);
});

it('generates OTP for the given app', function () {
    $app = 'test_app';
    $secret = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    // Set the configuration value for the app secret
    Config::set("simpleauthenticator.secrets.$app", $secret);

    $this->artisan('mfa:getotp', ['app' => $app])
        ->expectsOutput('Your OTP is: 900235')
        ->assertExitCode(0);
})->group('command');
