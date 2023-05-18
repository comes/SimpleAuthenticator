<?php

namespace Comes\SimpleAuthenticator\DTO;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

final class OneTimePassword
{
    public function __construct(private readonly string $otp, private readonly CarbonImmutable $validUntil, private readonly CarbonInterval $validityTimespan)
    {
        //
    }

    public function getOTP(): string
    {
        return $this->otp;
    }

    public function getValidUntil(): CarbonImmutable
    {
        return $this->validUntil;
    }

    public function getValidityTimespan(): CarbonInterval
    {
        return $this->validityTimespan;
    }
}
