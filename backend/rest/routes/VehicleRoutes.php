<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/VehicleService.php';

/**
 * @OA\Get(
 *     path="/vehicles",
 *     tags={"Vehicle"},
 *     summary="Get all vehicles",
 *     @OA\Response(response=200, description="List of vehicles"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /vehicles', function () {
    BaseRoutes::handle(fn() => (new VehicleService())->get_all());
});

/**
 * @OA\Get(
 *     path="/vehicles/{id}",
 *     tags={"Vehicle"},
 *     summary="Get one Vehicle by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The Vehicle"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Vehicle not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /vehicles/@id', function ($id) {
    BaseRoutes::handle(fn() => (new VehicleService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/vehicles",
 *     tags={"Vehicle"},
 *     summary="Create a Vehicle",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="Vehicle created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /vehicles', function () {
    BaseRoutes::handle(fn() => (new VehicleService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/vehicles/{id}",
 *     tags={"Vehicle"},
 *     summary="Update a Vehicle",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="Vehicle updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="Vehicle not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /vehicles/@id', function ($id) {
    BaseRoutes::handle(fn() => (new VehicleService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/vehicles/{id}",
 *     tags={"Vehicle"},
 *     summary="Delete a Vehicle",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Vehicle deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Vehicle not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /vehicles/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new VehicleService())->delete($id);
        return ['message' => 'Vehicle deleted successfully.'];
    });
});
