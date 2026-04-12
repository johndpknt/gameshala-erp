<?php

declare(strict_types=1);

/**
 * Shared helpers for WhatsApp OTP authentication endpoints.
 */
final class AuthCore
{
    public static function jsonResponse(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    }

    public static function ensurePostMethod(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            self::jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
            exit;
        }
    }

    public static function getRequiredEnv(string $key): string
    {
        $value = getenv($key);
        if ($value === false || trim($value) === '') {
            self::jsonResponse(['success' => false, 'message' => 'Server configuration error'], 500);
            exit;
        }

        return trim((string) $value);
    }

    public static function getPdo(): PDO
    {
        $host = self::getRequiredEnv('OTP_DB_HOST');
        $db   = self::getRequiredEnv('OTP_DB_NAME');
        $user = self::getRequiredEnv('OTP_DB_USER');
        $pass = self::getRequiredEnv('OTP_DB_PASS');
        $port = getenv('OTP_DB_PORT') ?: '3306';

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $db);

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    public static function normalizedMobile(?string $mobile): ?string
    {
        if ($mobile === null) {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', trim($mobile));
        if ($clean === null || $clean === '' || strlen($clean) < 8 || strlen($clean) > 15) {
            return null;
        }

        return $clean;
    }

    public static function requireWebhookSecret(): void
    {
        $expected = self::getRequiredEnv('WA_WEBHOOK_SECRET');
        $provided = $_SERVER['HTTP_X_WEBHOOK_SECRET'] ?? ($_POST['secret_key'] ?? '');

        if (! is_string($provided) || ! hash_equals($expected, trim($provided))) {
            self::jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }
    }
}
