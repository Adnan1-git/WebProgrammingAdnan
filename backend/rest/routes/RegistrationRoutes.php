<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/RegistrationService.php';

/**
 * @OA\Get(
 *     path="/registrations",
 *     tags={"Registration"},
 *     summary="Get all registrations",
 *     @OA\Response(response=200, description="List of registrations"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /registrations', function () {
    BaseRoutes::handle(fn() => (new RegistrationService())->get_all());
});

/**
 * @OA\Get(
 *     path="/registrations/{id}",
 *     tags={"Registration"},
 *     summary="Get one Registration by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The Registration"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Registration not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /registrations/@id', function ($id) {
    BaseRoutes::handle(fn() => (new RegistrationService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/registrations",
 *     tags={"Registration"},
 *     summary="Create a Registration",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="Registration created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /registrations', function () {
    BaseRoutes::handle(fn() => (new RegistrationService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/registrations/{id}",
 *     tags={"Registration"},
 *     summary="Update a Registration",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="Registration updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="Registration not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /registrations/@id', function ($id) {
    BaseRoutes::handle(fn() => (new RegistrationService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/registrations/{id}",
 *     tags={"Registration"},
 *     summary="Delete a Registration",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Registration deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Registration not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /registrations/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new RegistrationService())->delete($id);
        return ['message' => 'Registration deleted successfully.'];
    });
});
