<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    public function __construct(Review $model)
    {
        $this->service = new ReviewService($model);
    }
    
    public function createProductReview(Request $request, $id)
    {
        $result = $this->service->createProductReview($request, $id);
        return response()->json($result['data'], $result['code']);
    }
}
