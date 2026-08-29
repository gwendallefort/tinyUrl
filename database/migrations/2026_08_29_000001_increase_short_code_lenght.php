<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement(
            'ALTER TABLE short_urls MODIFY short_code VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL'
        );
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement(
            'ALTER TABLE short_urls MODIFY short_code VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL'
        );
    }
};
