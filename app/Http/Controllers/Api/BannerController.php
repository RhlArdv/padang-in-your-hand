<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    /**
     * Display a listing of active banners.
     */
    public function index(): JsonResponse
    {
        $banners = Banner::active()
            ->orderBy('order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $banners,
        ]);
    }
}
