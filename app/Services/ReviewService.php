<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ReviewService extends BaseService
{
    public function __construct($model)
    {
        $this->model = $model;
    }
    
    public function createProductReview(object $request, int $id): array
    {
        $validated = Validator::make(
            array_merge($request->all(), ['product_id' => $id]), 
            $this->setCreateProductReviewValidationRules()
        );
        
        if ($validated->fails()) {
            $this->response['code'] = 400;
            $this->response['data'] = $validated->errors();
            return $this->response;
        }
        
        $this->model->create([
            'product_id' => $id,
            'user_name'  => $request->input('user_name'),
            'rating'     => $request->input('rating'),
            'comment'    => $request->input('comment'),
        ]);
        
        $this->response['data'] = array_intersect_key($request->all(), array_flip($this->model->getFillable()));
        return $this->response;
    }
    
    private function setCreateProductReviewValidationRules(): array
    {
        return [
            'product_id' => 'exists:products,id',
            'user_name'  => 'required|string',
            'rating'     => 'required|numeric|between:1,10',
            'comment'    => 'required|string'
        ];
    }
}
