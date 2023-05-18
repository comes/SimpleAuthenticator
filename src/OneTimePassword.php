<?php

namespace Comes\SimpleAuthenticator;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

final class OneTimePassword
{
    public function __construct(
        private readonly string $oneTimePassword,
        private readonly CarbonImmutable $validUntil,
        private readonly CarbonInterval $validityTimespan
    ) {
        //
    }

    public function getOneTimePassword(): string
    {
        return $this->oneTimePassword;
    }

    public function getValidUntil(): CarbonImmutable
    {
        return $this->validUntil;
    }

    public function getValidFrom(): CarbonImmutable
    {
        return $this->getValidUntil()->sub($this->getValidityTimespan());
    }

    public function getValidityTimespan(): CarbonInterval
    {
        return $this->validityTimespan;
    }
}
