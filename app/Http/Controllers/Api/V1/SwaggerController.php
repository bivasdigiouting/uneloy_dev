<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="UOnly API Documentation",
 *     version="1.0.0",
 *     description="REST API documentation for UOnly application with Laravel Sanctum authentication",
 *
 *     @OA\Contact(
 *         email="admin@uonly.com",
 *         name="API Support"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format: Bearer {token}"
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="city", type="string", example="New York"),
 *     @OA\Property(property="state", type="string", example="NY"),
 *     @OA\Property(property="country", type="string", example="USA"),
 *     @OA\Property(property="postal_code", type="string", example="10001"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class SwaggerController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'UOnly API v1.0.0',
            'documentation' => 'Visit /api/documentation for Swagger UI',
            'endpoints' => [
                'auth' => [
                    'POST /api/v1/auth/register' => 'Register a new user',
                    'POST /api/v1/auth/login' => 'Login user',
                    'POST /api/v1/auth/logout' => 'Logout user (requires auth)',
                    'GET /api/v1/auth/profile' => 'Get user profile (requires auth)',
                    'PUT /api/v1/auth/profile' => 'Update user profile (requires auth)',
                ],
                'products' => [
                    'GET /api/v1/products' => 'Get list of products',
                    'POST /api/v1/products' => 'Create a new product (requires auth)',
                    'GET /api/v1/products/{id}' => 'Get product by ID',
                    'PUT /api/v1/products/{id}' => 'Update product (requires auth)',
                    'DELETE /api/v1/products/{id}' => 'Delete product (requires auth)',
                ],
                'banners' => [
                    'GET /api/v1/banners' => 'List banners (filters: banner_type, status)',
                    'GET /api/v1/banners/{id}' => 'Get banner details',
                ],
            ],
        ]);
    }
}
