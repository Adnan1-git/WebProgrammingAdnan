<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/ContactMessageService.php';

/**
 * @OA\Get(
 *     path="/contact-messages",
 *     tags={"ContactMessage"},
 *     summary="Get all contact-messages",
 *     @OA\Response(response=200, description="List of contact-messages"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /contact-messages', function () {
    BaseRoutes::handle(fn() => (new ContactMessageService())->get_all());
});

/**
 * @OA\Get(
 *     path="/contact-messages/{id}",
 *     tags={"ContactMessage"},
 *     summary="Get one ContactMessage by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The ContactMessage"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="ContactMessage not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /contact-messages/@id', function ($id) {
    BaseRoutes::handle(fn() => (new ContactMessageService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/contact-messages",
 *     tags={"ContactMessage"},
 *     summary="Create a ContactMessage",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="ContactMessage created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /contact-messages', function () {
    BaseRoutes::handle(fn() => (new ContactMessageService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/contact-messages/{id}",
 *     tags={"ContactMessage"},
 *     summary="Update a ContactMessage",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="ContactMessage updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="ContactMessage not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /contact-messages/@id', function ($id) {
    BaseRoutes::handle(fn() => (new ContactMessageService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/contact-messages/{id}",
 *     tags={"ContactMessage"},
 *     summary="Delete a ContactMessage",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="ContactMessage deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="ContactMessage not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /contact-messages/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new ContactMessageService())->delete($id);
        return ['message' => 'ContactMessage deleted successfully.'];
    });
});
