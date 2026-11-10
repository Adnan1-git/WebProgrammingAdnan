<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/InquiryService.php';

/**
 * @OA\Get(
 *     path="/inquiries",
 *     tags={"Inquiry"},
 *     summary="Get all inquiries",
 *     @OA\Response(response=200, description="List of inquiries"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /inquiries', function () {
    BaseRoutes::handle(fn() => (new InquiryService())->get_all());
});

/**
 * @OA\Get(
 *     path="/inquiries/{id}",
 *     tags={"Inquiry"},
 *     summary="Get one Inquiry by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The Inquiry"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Inquiry not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /inquiries/@id', function ($id) {
    BaseRoutes::handle(fn() => (new InquiryService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/inquiries",
 *     tags={"Inquiry"},
 *     summary="Create a Inquiry",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="Inquiry created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /inquiries', function () {
    BaseRoutes::handle(fn() => (new InquiryService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/inquiries/{id}",
 *     tags={"Inquiry"},
 *     summary="Update a Inquiry",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="Inquiry updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="Inquiry not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /inquiries/@id', function ($id) {
    BaseRoutes::handle(fn() => (new InquiryService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/inquiries/{id}",
 *     tags={"Inquiry"},
 *     summary="Delete a Inquiry",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Inquiry deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="Inquiry not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /inquiries/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new InquiryService())->delete($id);
        return ['message' => 'Inquiry deleted successfully.'];
    });
});
