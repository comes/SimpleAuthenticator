<?php

namespace Comes\SimpleAuthenticator;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;

class SimpleAuthenticator
{
    /**
     * Generate a Time-based one-time password.
     *
     * Note that this class uses the Unix times epoc.
     *
     * @link https://en.wikipedia.org/wiki/Time-based_one-time_password
     * @param string $secret
     * @param CarbonInterval|null $validityTimespan
     * @return OneTimePassword
     */
    public function generate(string $secret, ?CarbonInterval $validityTimespan = null): OneTimePassword
    {
        $validityTimespan ??= CarbonInterval::seconds(30);

        $count = $this->getCountOfWindows($validityTimespan->seconds);
        $oneTimePassword = $this->makePassword($secret, $count);
        $validUntil = $this->countEndsAt($count, $validityTimespan->totalSeconds);

        return new OneTimePassword($oneTimePassword, $validUntil, $validityTimespan);
    }

    private function makePassword(string $secret, int $count): string
    {
        $secretKey = $this->base32Decode($secret);

        // Pack time into binary string
        $packedTime = chr(0).chr(0).chr(0).chr(0).pack('N*', $count);

        // Generate HMAC-SHA1
        $hash = hash_hmac('SHA1', $packedTime, $secretKey, true);

        // Get offset
        $offset = ord(substr($hash, -1)) & 0x0F;

        // Calculate OTP
        $oneTimePassword = (
                (ord($hash[$offset + 0]) & 0x7F) << 24 |
                (ord($hash[$offset + 1]) & 0xFF) << 16 |
                (ord($hash[$offset + 2]) & 0xFF) << 8 |
                (ord($hash[$offset + 3]) & 0xFF)
            ) % pow(10, 6);

        // Zero-padding if necessary
        return str_pad($oneTimePassword, 6, '0', STR_PAD_LEFT);
    }

    private function base32Decode($base32): string
    {
        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $base32charsFlipped = array_flip(str_split($base32chars));

        $output = '';

        $i = 0;
        $buffer = 0;
        $bufferSize = 0;

        // Reverse the base32 encoding and convert the secret key back to its original binary form.
        // Accumulate bits into a buffer and convert them into bytes, appending them to the output.
        // Throw an exception if an invalid base32 character is encountered.
        while ($i < strlen($base32)) {
            $char = strtoupper($base32[$i]);

            if (! isset($base32charsFlipped[$char])) {
                throw new \RuntimeException('Invalid base32 character: '.$char);
            }

            $buffer <<= 5;
            $buffer |= $base32charsFlipped[$char];
            $bufferSize += 5;

            if ($bufferSize >= 8) {
                $bufferSize -= 8;
                $output .= chr(($buffer & (0xFF << $bufferSize)) >> $bufferSize);
            }

            $i++;
        }

        return $output;
    }

    private function unixTime(): int
    {
        return CarbonImmutable::now()->floorSecond()->timestamp;
    }

    private function getCountOfWindows(int $window): int
    {
        return floor($this->unixTime() / $window);
    }

    private function countEndsAt(int $count, int $window): CarbonImmutable
    {
        return CarbonImmutable::createFromTimestamp(($count + 1) * $window);
    }
}
