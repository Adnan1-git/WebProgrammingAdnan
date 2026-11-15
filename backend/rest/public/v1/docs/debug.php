<?php
// TEMPORARY: put in backend/rest/public/v1/docs/ and open it in the browser. Delete afterwards.
ini_set('display_errors', '1');
ini_set('html_errors', '0');
error_reporting(E_ALL);
header('Content-Type: text/plain');

register_shutdown_function(function () {
    $e = error_get_last();
    if ($e) {
        echo "\n\n=== FATAL / LAST ERROR ===\n" . print_r($e, true);
    }
});

echo "1. script started, PHP " . PHP_VERSION . " (" . PHP_SAPI . ")\n";
echo "   php.ini: " . php_ini_loaded_file() . "\n";
echo "   memory_limit: " . ini_get('memory_limit') . ", max_execution_time: " . ini_get('max_execution_time') . "\n";
flush();

$autoload = __DIR__ . '/../../../../vendor/autoload.php';
echo "2. autoload path: " . realpath($autoload) . "\n";
if (!is_file($autoload)) {
    exit("   NOT FOUND\n");
}
require $autoload;
echo "3. autoload loaded\n";
echo "   OpenApi\\Generator exists: " . (class_exists('OpenApi\\Generator') ? 'yes' : 'NO') . "\n";
flush();

$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$_SERVER['REQUEST_URI'] = '/rest/public/v1/docs/swagger.php';
define('API_BASE_URL', 'http://localhost:8000/rest/');

$files = array_merge([__DIR__ . '/doc_setup.php'], glob(__DIR__ . '/paths/*.php'));
echo "4. files to load:\n   " . implode("\n   ", $files) . "\n";
flush();

foreach ($files as $f) {
    require_once $f;
    echo "   loaded " . basename($f) . "\n";
    flush();
}

echo "5. generating...\n";
flush();
$openapi = (new \OpenApi\Generator())->generate($files);
echo "6. DONE, JSON length: " . strlen($openapi->toJson()) . "\n";