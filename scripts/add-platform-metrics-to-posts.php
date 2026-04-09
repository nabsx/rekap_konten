<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "[INFO] Starting migration: add platform metrics to posts table\n";

try {
    if (Schema::hasTable('posts')) {

        Schema::table('posts', function (Blueprint $table) {

            if (!Schema::hasColumn('posts', 'content_type')) {
                $table->string('content_type')->nullable()->after('url');
                echo "[OK] Added content_type column\n";
            } else {
                echo "[SKIP] content_type column already exists\n";
            }

            if (!Schema::hasColumn('posts', 'followers')) {
                $table->integer('followers')->nullable()->after('content_type');
                echo "[OK] Added followers column\n";
            } else {
                echo "[SKIP] followers column already exists\n";
            }

            if (!Schema::hasColumn('posts', 'viewers')) {
                $table->integer('viewers')->nullable()->after('followers');
                echo "[OK] Added viewers column\n";
            } else {
                echo "[SKIP] viewers column already exists\n";
            }

            if (!Schema::hasColumn('posts', 'subscribers')) {
                $table->integer('subscribers')->nullable()->after('viewers');
                echo "[OK] Added subscribers column\n";
            } else {
                echo "[SKIP] subscribers column already exists\n";
            }
        });

        echo "\n[SUCCESS] Migration completed successfully!\n";

    } else {
        echo "[ERROR] posts table does not exist\n";
        exit(1);
    }

} catch (\Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}