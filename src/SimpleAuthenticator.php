<?php

namespace Comes\SimpleAuthenticator;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Comes\SimpleAuthenticator\DTO\OneTimePassword;
use Illuminate\Support\Carbon;

class SimpleAuthenticator
{
    public function generateOTP(string $secret, ?CarbonInterval $validityTimespan = null): OneTimePassword
    {
        $validityTimespan ??= CarbonInterval::seconds(30);

        // Tokens are only available for 30 seconds.
        $now = Carbon::now()->floorSecond()->toImmutable();
        $time = floor($now->timestamp / $validityTimespan->totalSeconds);

        $secretKey = $this->base32Decode($secret);

        // Pack time into binary string
        $packedTime = chr(0).chr(0).chr(0).chr(0).pack('N*', $time);

        // Generate HMAC-SHA1
        $hash = hash_hmac('SHA1', $packedTime, $secretKey, true);

        // Get offset
        $offset = ord(substr($hash, -1)) & 0x0F;

        // Calculate OTP
        $otp = (
            (ord($hash[$offset + 0]) & 0x7F) << 24 |
            (ord($hash[$offset + 1]) & 0xFF) << 16 |
            (ord($hash[$offset + 2]) & 0xFF) << 8 |
            (ord($hash[$offset + 3]) & 0xFF)
        ) % pow(10, 6);

        // Zero-padding if necessary
        $otp = str_pad($otp, 6, '0', STR_PAD_LEFT);

        $validUntil = CarbonImmutable::createFromTimestamp(($time + 1) * $validityTimespan->totalSeconds);

        return new OneTimePassword($otp, $validUntil, $validityTimespan);
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
}
