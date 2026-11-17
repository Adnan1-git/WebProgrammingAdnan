<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/UserService.php';

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"User"},
 *     summary="Get all users",
 *     @OA\Response(response=200, description="List of users"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /users', function () {
    BaseRoutes::handle(fn() => (new UserService())->get_all());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"User"},
 *     summary="Get one User by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The User"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="User not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /users/@id', function ($id) {
    BaseRoutes::handle(fn() => (new UserService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"User"},
 *     summary="Create a User",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="User created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /users', function () {
    BaseRoutes::handle(fn() => (new UserService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"User"},
 *     summary="Update a User",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="User updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="User not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    BaseRoutes::handle(fn() => (new UserService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"User"},
 *     summary="Delete a User",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="User deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="User not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new UserService())->delete($id);
        return ['message' => 'User deleted successfully.'];
    });
});
