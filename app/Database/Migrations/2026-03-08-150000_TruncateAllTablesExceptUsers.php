<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Truncates all tables (deletes all rows, keeps schema) except user-related tables.
 * Excluded: users, user_activities
 */
class TruncateAllTablesExceptUsers extends Migration
{
    /** @var list<string> Tables to never truncate (user / auth data) */
    protected array $excludedTables = ['users', 'user_activities'];

    public function up(): void
    {
        $db    = $this->db;
        $prefix = $db->DBPrefix;
        $tables = $db->listTables(true);
        if (! is_array($tables)) {
            return;
        }
        $excluded = [];
        foreach ($this->excludedTables as $name) {
            $excluded[] = $prefix . $name;
        }
        $toTruncate = array_diff($tables, $excluded);
        if (empty($toTruncate)) {
            return;
        }
        $platform = $db->getPlatform();
        if (stripos($platform, 'MySQL') !== false) {
            $db->query('SET FOREIGN_KEY_CHECKS = 0');
        }
        foreach ($toTruncate as $table) {
            $db->query('TRUNCATE TABLE ' . $db->escapeIdentifiers($table));
        }
        if (stripos($platform, 'MySQL') !== false) {
            $db->query('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    public function down(): void
    {
        // No-op: data cannot be restored
    }
}
