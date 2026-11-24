<?php

use OTPHP\TOTP as OtpTotp;

class Totp
{
    public static function generate_secret(): string
    {
        return OtpTotp::generate()->getSecret();
    }

    public static function provisioning_uri(string $label, string $secret, string $issuer = 'MyApp'): string
    {
        $otp = OtpTotp::createFromSecret($secret);
        $otp->setLabel($label);
        $otp->setIssuer($issuer);
        return $otp->getProvisioningUri();
    }

    public static function verify(string $secret, string $code): bool
    {
        $otp = OtpTotp::createFromSecret($secret);
        return $otp->verify($code, null, 29); // allows about 30s clock drift
    }
}