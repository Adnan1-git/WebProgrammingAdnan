<?php
require_once __DIR__ . '/../services/WeatherService.php';

Flight::route('GET /weather', function () {
    $city = trim((string) (Flight::request()->query['city'] ?? ''));

    if ($city === '' || mb_strlen($city) > 100) {
        Flight::json(['error' => 'Query parameter "city" is required (max 100 characters).'], 400);
        return;
    }

    try {
        $service = new WeatherService();
        Flight::json($service->getWeatherByCity($city));
    } catch (ExternalApiException $e) {
        Flight::json(['error' => $e->getMessage()], $e->getHttpStatus());
    } catch (Throwable $e) {
        error_log('Weather endpoint: ' . $e->getMessage());
        Flight::json(['error' => 'Unexpected server error.'], 500);
    }
});