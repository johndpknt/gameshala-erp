<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_core.php';

AuthCore::ensurePostMethod();

$mobileInput   = $_POST['mobile_number'] ?? null;
$pinInputValue = $_POST['pin'] ?? null;

$mobileNumber = AuthCore::normalizedMobile(is_string($mobileInput) ? $mobileInput : null);
$pin          = is_scalar($pinInputValue) ? trim((string) $pinInputValue) : '';

if ($mobileNumber === null || ! preg_match('/^\d{4}$/', $pin)) {
    AuthCore::jsonResponse(['success' => false, 'message' => 'Invalid or expired PIN'], 422);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = AuthCore::getPdo();
    $pdo->beginTransaction();

    $findToken = $pdo->prepare(
        "SELECT id
         FROM gs_auth_tokens
         WHERE mobile_number = :mobile_number
           AND otp_code = :otp_code
           AND status = 'active'
           AND expires_at >= NOW()
         ORDER BY id DESC
         LIMIT 1
         FOR UPDATE"
    );
    $findToken->execute([
        ':mobile_number' => $mobileNumber,
        ':otp_code'      => $pin,
    ]);
    $token = $findToken->fetch();

    if (! $token) {
        $pdo->rollBack();
        AuthCore::jsonResponse(['success' => false, 'message' => 'Invalid or expired PIN']);
        exit;
    }

    $markUsed = $pdo->prepare(
        "UPDATE gs_auth_tokens
         SET status = 'used'
         WHERE id = :id"
    );
    $markUsed->execute([':id' => (int) $token['id']]);

    $upsertUser = $pdo->prepare(
        "INSERT INTO gs_users (mobile_number, created_at, updated_at)
         VALUES (:mobile_number, NOW(), NOW())
         ON DUPLICATE KEY UPDATE updated_at = NOW()"
    );
    $upsertUser->execute([':mobile_number' => $mobileNumber]);

    $getUser = $pdo->prepare("SELECT id FROM gs_users WHERE mobile_number = :mobile_number LIMIT 1");
    $getUser->execute([':mobile_number' => $mobileNumber]);
    $user = $getUser->fetch();

    $pdo->commit();

    $_SESSION['gs_user_id']       = (int) ($user['id'] ?? 0);
    $_SESSION['gs_mobile_number'] = $mobileNumber;
    $_SESSION['gs_logged_in']     = true;

    AuthCore::jsonResponse(['success' => true, 'message' => 'Login successful']);
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    AuthCore::jsonResponse(['success' => false, 'message' => 'Invalid or expired PIN'], 500);
}
