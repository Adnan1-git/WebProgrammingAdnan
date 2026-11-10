<?php
require_once __DIR__ . '/BaseRoutes.php';
require_once __DIR__ . '/../rest/services/FinancingRequestService.php';

/**
 * @OA\Get(
 *     path="/financing-requests",
 *     tags={"FinancingRequest"},
 *     summary="Get all financing-requests",
 *     @OA\Response(response=200, description="List of financing-requests"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /financing-requests', function () {
    BaseRoutes::handle(fn() => (new FinancingRequestService())->get_all());
});

/**
 * @OA\Get(
 *     path="/financing-requests/{id}",
 *     tags={"FinancingRequest"},
 *     summary="Get one FinancingRequest by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="The FinancingRequest"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="FinancingRequest not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /financing-requests/@id', function ($id) {
    BaseRoutes::handle(fn() => (new FinancingRequestService())->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/financing-requests",
 *     tags={"FinancingRequest"},
 *     summary="Create a FinancingRequest",
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=201, description="FinancingRequest created"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /financing-requests', function () {
    BaseRoutes::handle(fn() => (new FinancingRequestService())->add(BaseRoutes::body()), 201);
});

/**
 * @OA\Put(
 *     path="/financing-requests/{id}",
 *     tags={"FinancingRequest"},
 *     summary="Update a FinancingRequest",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object")),
 *     @OA\Response(response=200, description="FinancingRequest updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="FinancingRequest not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /financing-requests/@id', function ($id) {
    BaseRoutes::handle(fn() => (new FinancingRequestService())->update($id, BaseRoutes::body()));
});

/**
 * @OA\Delete(
 *     path="/financing-requests/{id}",
 *     tags={"FinancingRequest"},
 *     summary="Delete a FinancingRequest",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="FinancingRequest deleted"),
 *     @OA\Response(response=400, description="Invalid ID"),
 *     @OA\Response(response=404, description="FinancingRequest not found"),
 *     @OA\Response(response=409, description="Cannot delete: related records exist"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /financing-requests/@id', function ($id) {
    BaseRoutes::handle(function () use ($id) {
        (new FinancingRequestService())->delete($id);
        return ['message' => 'FinancingRequest deleted successfully.'];
    });
});
