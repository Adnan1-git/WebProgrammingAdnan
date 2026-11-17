<?php
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Weather',
    properties: [
        new OA\Property(property: 'city', type: 'string', example: 'Sarajevo'),
        new OA\Property(property: 'country', type: 'string', example: 'BA'),
        new OA\Property(property: 'temperature', type: 'number', format: 'float', example: 14.2),
        new OA\Property(property: 'feels_like', type: 'number', format: 'float', example: 13.1),
        new OA\Property(property: 'humidity', type: 'integer', example: 72),
        new OA\Property(property: 'description', type: 'string', example: 'light rain'),
        new OA\Property(property: 'wind_speed', type: 'number', format: 'float', example: 3.4),
    ]
)]
class WeatherPaths
{
    #[OA\Get(
        path: '/weather',
        summary: 'Get current weather for a city (external API)',
        description: 'Calls the OpenWeatherMap API and returns the current weather. The API key is kept on the server.',
        tags: ['Weather'],
        parameters: [
            new OA\Parameter(
                name: 'city',
                in: 'query',
                required: true,
                description: 'City name',
                schema: new OA\Schema(type: 'string'),
                example: 'Sarajevo'
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Current weather',
                content: new OA\JsonContent(ref: '#/components/schemas/Weather')),
            new OA\Response(response: 400, description: 'Missing or invalid city parameter',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'City not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 500, description: 'Server error or API key not configured',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 502, description: 'External API error or invalid response',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 503, description: 'External API rate limit reached',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 504, description: 'External API timed out',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
        ]
    )]
    public function getWeather() {}
}