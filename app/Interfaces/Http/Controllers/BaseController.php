<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Routing\Controller;

/**
 * @OA\Info(
 * title="User Management API",
 * version="1.0.0",
 * description="API endpoints for managing users"
 * )
 * * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="API Server"
 * )
 */

abstract class BaseController extends Controller {}
