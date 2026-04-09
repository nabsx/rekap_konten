<?php
/**
 * Setup script to ensure platforms table exists with proper data
 * Run with: php artisan tinker < scripts/setup-platforms.php
 */

// Check if platforms table exists
if (Schema::hasTable('platforms')) {
    echo "Platforms table already exists\n";
    
    // Seed platforms
    $platformSeeder = new \Database\Seeders\PlatformSeeder();
    $platformSeeder->run();
    echo "Platforms seeded successfully\n";
} else {
    echo "Platforms table does not exist. Run migrations first.\n";
}
