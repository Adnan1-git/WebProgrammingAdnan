<?php

require_once __DIR__ . '/../../config/config.php';

class ExternalApiException extends Exception
{
    private int $httpStatus;

    public function __construct(string $message, int $httpStatus = 502)
    {
        parent::__construct($message);
        $this->httpStatus = $httpStatus;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}

class WeatherService
{
    public function getWeatherByCity(string $city): array
    {
        $apiKey = Config::WEATHER_API_KEY();
        if ($apiKey === '' || $apiKey === 'PUT_YOUR_KEY_HERE') {
            error_log('WeatherService: API key is not configured');
            throw new ExternalApiException('Weather service is not configured.', 500);
        }

        $url = Config::WEATHER_API_BASE_URL() . '/weather?' . http_build_query([
            'q'     => $city,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => Config::WEATHER_CONNECT_TIMEOUT(),
            CURLOPT_TIMEOUT        => Config::WEATHER_TIMEOUT(),
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);

        $body   = curl_exec($ch);
        $errno  = curl_errno($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Network-level failures (never log $url, it contains the key)
        if ($errno === CURLE_OPERATION_TIMEDOUT) {
            error_log('WeatherService: request timed out');
            throw new ExternalApiException('Weather service timed out. Please try again.', 504);
        }
        if ($errno !== 0 || $body === false) {
            error_log('WeatherService: connection error code ' . $errno);
            throw new ExternalApiException('Weather service is unreachable.', 502);
        }

        // HTTP-level failures
        if ($status === 404) {
            throw new ExternalApiException('City not found.', 404);
        }
        if ($status === 401) {
            error_log('WeatherService: upstream rejected the API key');
            throw new ExternalApiException('Weather service authentication failed.', 502);
        }
        if ($status === 429) {
            throw new ExternalApiException('Weather service rate limit reached. Try again later.', 503);
        }
        if ($status < 200 || $status >= 300) {
            error_log('WeatherService: upstream returned HTTP ' . $status);
            throw new ExternalApiException('Weather service returned an error.', 502);
        }

        $data = json_decode($body, true);
        if (!is_array($data) || !isset($data['main']['temp'])) {
            throw new ExternalApiException('Weather service returned an invalid response.', 502);
        }

        // Map to our own format so the frontend doesn't depend on the third-party shape
        return [
            'city'        => $data['name'] ?? $city,
            'country'     => $data['sys']['country'] ?? null,
            'temperature' => round($data['main']['temp'], 1),
            'feels_like'  => round($data['main']['feels_like'] ?? $data['main']['temp'], 1),
            'humidity'    => $data['main']['humidity'] ?? null,
            'description' => $data['weather'][0]['description'] ?? null,
            'wind_speed'  => $data['wind']['speed'] ?? null,
        ];
    }
}