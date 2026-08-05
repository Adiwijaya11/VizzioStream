<?php
require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: text/plain');

echo "PHP version: " . PHP_VERSION . "\n";

try {
    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "Laravel version: " . \Illuminate\Foundation\Application::VERSION . "\n";
    echo "class_exists ViewServiceProvider: " . (class_exists(\Illuminate\View\ViewServiceProvider::class) ? 'yes' : 'no') . "\n";
    echo "class_exists DefaultProviders: " . (class_exists(\Illuminate\Support\DefaultProviders::class) ? 'yes' : 'no') . "\n";
    echo "vendor/laravel/framework/config/view.php exists: " . (file_exists(__DIR__ . '/../vendor/laravel/framework/config/view.php') ? 'yes' : 'no') . "\n";
    echo "app/config/view.php exists: " . (file_exists(__DIR__ . '/../config/view.php') ? 'yes' : 'no') . "\n";
    echo "storage/framework/views writable: " . (is_writable(__DIR__ . '/../storage/framework/views') ? 'yes' : (file_exists(__DIR__ . '/../storage/framework/views') ? 'exists-not-writable' : 'not-exists')) . "\n";
    echo "bootstrap/cache writable: " . (is_writable(__DIR__ . '/../bootstrap/cache') ? 'yes' : 'no') . "\n";

    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();

    echo "Boot: OK\n";
    echo "view bound: " . ($app->bound('view') ? 'yes' : 'no') . "\n";

    $providers = $app->getLoadedProviders();
    echo "Total loaded providers: " . count($providers) . "\n";

    echo "\n--- ALL PROVIDERS ---\n";
    foreach ($providers as $p => $bool) {
        echo " - $p\n";
    }
} catch (\Throwable $e) {
    echo "BOOT ERROR: " . get_class($e) . ": " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo $e->getTraceAsString() . "\n";
}
