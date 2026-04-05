<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Sets password_hash for admin@example.com (bcrypt only; no plaintext in source).
 */
class UpdateAdminExamplePasswordHash extends Migration
{
    private const EMAIL = 'admin@example.com';

    /** @var string Previous bcrypt for default "password" */
    private const PREVIOUS_HASH = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    /** @var string Replacement bcrypt hash for admin@example.com */
    private const NEW_HASH = '$2y$12$KtlvG2i6XFx7RIi4MbZvvO.70vOYGaodahkLRauTwm0T3yt5bhZmm';

    public function up(): void
    {
        $this->db->table('users')->where('email', self::EMAIL)->update([
            'password_hash' => self::NEW_HASH,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    public function down(): void
    {
        $this->db->table('users')->where('email', self::EMAIL)->update([
            'password_hash' => self::PREVIOUS_HASH,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
    }
}
