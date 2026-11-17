<?php
use OpenApi\Attributes as OA;

class ClubPaths
{
    #[OA\Get(
        path: '/clubs/{id}',
        tags: ['clubs'],
        summary: 'Get one club',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                             schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'The club',
                            content: new OA\JsonContent(ref: '#/components/schemas/Club')),
            new OA\Response(response: 404, description: 'Club not found',
                            content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function getClubsById() {}
}

