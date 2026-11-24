<?php
use OpenApi\Attributes as OA;





#[OA\Info(
    title: 'CampusEvents API',
    description: 'REST API of CampusEvents – student events at IBU – IT 2001 Web Programming.',
    version: '1.0',
    contact: new OA\Contact(name: 'Web Programming', email: 'web2001programming@gmail.com')
)]
#[OA\Server(url: API_BASE_URL, description: 'This server')]
#[OA\Schema(
    schema: 'Error',
    properties: [
        new OA\Property(property: 'error', type: 'string', example: 'Field \'title\' is required.')
    ]
)]
#[OA\Schema(
    schema: 'Message',
    properties: [new OA\Property(property: 'message', type: 'string', example: 'Event deleted.')]
)]
#[OA\Schema(
    schema: 'Club',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Robotics Club'),
        new OA\Property(property: 'description', type: 'string', example: 'Builds and programs robots.')
    ]
)]
#[OA\Schema(
    schema: 'Registration',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'event_id', type: 'integer', example: 3),
        new OA\Property(property: 'status', type: 'string', enum: ['active', 'cancelled', 'attended'], example: 'active'),
        new OA\Property(property: 'registered_at', type: 'string', format: 'date-time', example: '2026-11-13 10:00:00')
    ]
)]
#[OA\Schema(
    schema: 'RegistrationInput',
    required: ['user_id', 'event_id'],
    properties: [
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'event_id', type: 'integer', example: 3)
    ]
)]
class OpenApiSpec {}