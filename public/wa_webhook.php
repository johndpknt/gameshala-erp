<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_core.php';

AuthCore::ensurePostMethod();
AuthCore::requireWebhookSecret();

$sender       = $_POST['sender'] ?? null;
$mobileNumber = AuthCore::normalizedMobile(is_string($sender) ? $sender : null);

if ($mobileNumber === null) {
    AuthCore::jsonResponse(['success' => false, 'message' => 'Invalid sender/mobile number'], 422);
    exit;
}

try {
    $pdo = AuthCore::getPdo();
    $pin = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

    $pdo->beginTransaction();

    $expireOldTokens = $pdo->prepare(
        "UPDATE gs_auth_tokens
         SET status = 'expired'
         WHERE mobile_number = :mobile_number
           AND status = 'active'"
    );
    $expireOldTokens->execute([':mobile_number' => $mobileNumber]);

    $insertToken = $pdo->prepare(
        "INSERT INTO gs_auth_tokens (mobile_number, otp_code, status, created_at, expires_at)
         VALUES (:mobile_number, :otp_code, 'active', NOW(), DATE_ADD(NOW(), INTERVAL 10 MINUTE))"
    );
    $insertToken->execute([
        ':mobile_number' => $mobileNumber,
        ':otp_code'      => $pin,
    ]);

    $pdo->commit();

    AuthCore::jsonResponse([
        'replies' => [
            ['message' => "Welcome to Gameshaala! Your login PIN is: {$pin}"],
        ],
    ]);
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    AuthCore::jsonResponse(['success' => false, 'message' => 'Unable to generate PIN'], 500);
}
