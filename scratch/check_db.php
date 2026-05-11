<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$columns = Schema::getColumnListing('schools');
echo "Columns in 'schools' table:\n";
print_r($columns);

try {
    $results = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'schools'");
    echo "\nDetailed columns from information_schema:\n";
    foreach($results as $row) {
        echo "- " . $row->column_name . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
