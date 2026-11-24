<?php
require_once __DIR__ . '/../vendor/autoload.php';   // adjust if vendor/ is elsewhere
require_once __DIR__ . '/config.php';               // defines API_BASE_URL, DB constants

// DAOs and services
foreach (glob(__DIR__ . '/dao/*Dao.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/services/*Service.php') as $file) {
    require_once $file;
}

// CORS (so Swagger and your frontend can call the API)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Register services so routes can call Flight::registrationService()
Flight::register('registrationService', 'RegistrationService');
// Flight::register('weatherService', 'WeatherService');
// ...one line per service

// Load all routes
require_once __DIR__ . '/routes/routes.php';

Flight::start();