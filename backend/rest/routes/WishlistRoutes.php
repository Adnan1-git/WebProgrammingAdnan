<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/WishlistService.php';

/**
 * @OA\Get(
 *     path="/wishlist",
 *     tags={"Wishlist"},
 *     summary="Get all wishlist",
 *     @OA\Response(response=200, description="List of wishlist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /wishlist', function () {
    BaseRoutes::handle(fn() => (new WishlistService())->get_all());
});

/**
 * @OA\Get(
 *     path="/wishlist/{id}",
 *     tags={"Wishlist"},
 *     summary="Get one Wishlist by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The Wishlist"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Wishlist not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /wishlist/@id', function ($id) {
    BaseRoutes::handle(fn() => (new WishlistService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/wishlist",
 *     tags={"Wishlist"},
 *     summary="Create a Wishlist",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="Wishlist created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /wishlist', function () {
    BaseRoutes::handle(fn() => (new WishlistService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/wishlist/{id}",
 *     tags={"Wishlist"},
 *     summary="Update a Wishlist",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="Wishlist updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="Wishlist not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /wishlist/@id', function ($id) {
    BaseRoutes::handle(fn() => (new WishlistService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/wishlist/{id}",
 *     tags={"Wishlist"},
 *     summary="Delete a Wishlist",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Wishlist deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Wishlist not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /wishlist/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new WishlistService())->delete($id);
        return ['message' => 'Wishlist deleted successfully.'];
    });
});
