<?php

use OTPHP\TOTP;

class TotpHelper
{
    public static function generate_secret(): string
    {
        return TOTP::create()->getSecret();
    }

    public static function generate(): string
    {
        return self::generate_secret();
    }

    public static function provisioning_uri(string $label, string $secret, string $issuer = 'MyApp'): string
    {
        $otp = TOTP::create($secret);
        $otp->setLabel($label);
        $otp->setIssuer($issuer);
        return $otp->getProvisioningUri();
    }

    public static function verify(string $secret, string $code): bool
    {
        return TOTP::create($secret)->verify($code, null, 29);
    }
}