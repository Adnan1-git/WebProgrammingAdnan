<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/EventService.php';

/**
 * @OA\Get(
 *     path="/events",
 *     tags={"Event"},
 *     summary="Get all events",
 *     @OA\Response(response=200, description="List of events"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /events', function () {
    BaseRoutes::handle(fn() => (new EventService())->get_all());
});

/**
 * @OA\Get(
 *     path="/events/{id}",
 *     tags={"Event"},
 *     summary="Get one Event by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The Event"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Event not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /events/@id', function ($id) {
    BaseRoutes::handle(fn() => (new EventService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/events",
 *     tags={"Event"},
 *     summary="Create a Event",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="Event created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /events', function () {
    BaseRoutes::handle(fn() => (new EventService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/events/{id}",
 *     tags={"Event"},
 *     summary="Update a Event",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="Event updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="Event not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /events/@id', function ($id) {
    BaseRoutes::handle(fn() => (new EventService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/events/{id}",
 *     tags={"Event"},
 *     summary="Delete a Event",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Event deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Event not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /events/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new EventService())->delete($id);
        return ['message' => 'Event deleted successfully.'];
    });
});
