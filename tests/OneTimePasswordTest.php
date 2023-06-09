<?php

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Comes\SimpleAuthenticator\OneTimePassword;

it('can create a OneTimePassword DTO', function () {
    $oneTimePassword = '123456';
    $validUntil = CarbonImmutable::now()->addMinutes(5);
    $validityTimespan = CarbonInterval::seconds(30);

    $dto = new OneTimePassword($oneTimePassword, $validUntil, $validityTimespan);

    expect($dto)->toBeInstanceOf(OneTimePassword::class);
    expect($dto->getOneTimePassword())->toBe($oneTimePassword);
    expect($dto->getValidUntil())->toBeInstanceOf(CarbonImmutable::class);
    expect($dto->getValidUntil())->toBe($validUntil);
    expect($dto->getValidityTimespan())->toBe($validityTimespan);
});
