<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "[INFO] Adding likes column...\n";

if (!Schema::hasColumn('posts', 'likes')) {
    Schema::table('posts', function (Blueprint $table) {
        $table->integer('likes')->nullable()->after('subscribers');
    });

    echo "✓ Added 'likes' column to posts table\n";
} else {
    echo "✓ Column 'likes' already exists\n";
}