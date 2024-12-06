<?php

namespace App\services;

use DateInterval;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

define("FORMAT_DATE", "Y-m-d H:i:s");

class JWTService
{
    public function __construct(private string $secretKey) {}

    public function generateSubscribeConfirmationToken(string $email)
    {
        $expiredAt = new DateTime;
        $expiredAt->modify('+5 minutes');
        $payload = [
            "expiredAt" => $expiredAt->format(FORMAT_DATE),
            "email" => $email
        ];
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function generateForgotPasswordToken(string $email, string $hashPassword)
    {
        $expiredAt = new DateTime;
        $expiredAt->modify('+5 minutes');
        $payload = [
            "expiredAt" => $expiredAt->format(FORMAT_DATE),
            "email" => $email
        ];
        return JWT::encode($payload, $this->secretKey . $hashPassword, 'HS256');
    }

    public function generateEmailAuthenticationToken(string $email)
    {
        $expiredAt = new DateTime;
        $expiredAt->modify('+5 minutes');
        $payload = [
            "expiredAt" => $expiredAt->format(FORMAT_DATE),
            "email" => $email
        ];
        return JWT::encode($payload, $this->secretKey . $email, 'HS256');
    }

    public function decode(string $token, string $extSecretKey = ""): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey . $extSecretKey, 'HS256'));
            return get_object_vars($decoded);
        } catch (Exception $e) {
            return null;
        }
    }
}
