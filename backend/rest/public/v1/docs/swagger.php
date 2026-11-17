<?php
/*
 * Generates the OpenAPI (JSON) document from the PHP 8 attributes in doc_setup.php (API info, schemas)
 * and paths/*.php (one class per route file). Swagger UI (index.html) reads it.
 */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');

// Buffer everything so stray warnings can never corrupt the JSON
ob_start();

try {
    require __DIR__ . '/../../../../vendor/autoload.php';

    // Base URL of the API, e.g. http://localhost:8000/rest/ (works locally and after deployment)
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $path = preg_replace('#public/v1/docs/swagger\.php$#', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    define('API_BASE_URL', $scheme . '://' . $_SERVER['HTTP_HOST'] . $path);

    // swagger-php reads the attributes with reflection, so the documentation classes must be loaded first
    $files = array_merge([__DIR__ . '/doc_setup.php'], glob(__DIR__ . '/paths/*.php'));
    foreach ($files as $file) {
        require_once $file;
    }

    $openapi = (new \OpenApi\Generator())->generate($files);
    $json = $openapi->toJson();

    ob_end_clean();
    header('Content-Type: application/json');
    echo $json;
} catch (\Throwable $e) {
    // Show the real reason in Swagger UI instead of a blank "Internal Server Error"
    ob_end_clean();
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage(),
        'file'  => $e->getFile(),
        'line'  => $e->getLine(),
        'php'   => PHP_VERSION,
        'sapi'  => PHP_SAPI,
        'ini'   => php_ini_loaded_file(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}