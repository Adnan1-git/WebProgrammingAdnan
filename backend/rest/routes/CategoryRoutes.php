<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/CategoryService.php';

/**
 * @OA\Get(
 *     path="/categories",
 *     tags={"Category"},
 *     summary="Get all categories",
 *     @OA\Response(response=200, description="List of categories"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /categories', function () {
    BaseRoutes::handle(fn() => (new CategoryService())->get_all());
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     tags={"Category"},
 *     summary="Get one Category by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The Category"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Category not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /categories/@id', function ($id) {
    BaseRoutes::handle(fn() => (new CategoryService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/categories",
 *     tags={"Category"},
 *     summary="Create a Category",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="Category created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /categories', function () {
    BaseRoutes::handle(fn() => (new CategoryService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"Category"},
 *     summary="Update a Category",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="Category updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="Category not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /categories/@id', function ($id) {
    BaseRoutes::handle(fn() => (new CategoryService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"Category"},
 *     summary="Delete a Category",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Category deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Category not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /categories/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new CategoryService())->delete($id);
        return ['message' => 'Category deleted successfully.'];
    });
});
