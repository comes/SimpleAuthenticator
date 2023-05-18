<?php

use Comes\SimpleAuthenticator\OneTimePassword;
use Comes\SimpleAuthenticator\SimpleAuthenticator;
use Illuminate\Support\Carbon;

beforeEach(function () {
    // Mock the time to a fixed timestamp for predictable OTP generation
    Carbon::setTestNow(Carbon::parse('2023-01-01 00:00:12'));
});

it('generates OTP correctly', function () {
    $secret = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $authenticator = new SimpleAuthenticator;

    $expectedOTP = '900235';
    $dto = $authenticator->generate($secret);

    expect($dto)->toBeInstanceOf(OneTimePassword::class);
    expect($dto->getOneTimePassword())->toBe($expectedOTP);
});

it('throws exception for invalid base32 character', function () {
    $secret = 'INVALID_KEY';
    $authenticator = new SimpleAuthenticator;

    expect(function () use ($authenticator, $secret) {
        $authenticator->generate($secret);
    })->toThrow(\RuntimeException::class, 'Invalid base32 character');
});
