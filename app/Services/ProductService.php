<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ProductService extends BaseService
{
    public function __construct($model)
    {
        $this->model = $model;
    }
    
    public function showAllProducts(): array
    {
        $result = $this->model->with('reviews')->get();
        
        if ($result->isEmpty()) {
            $this->response['data'] = ['message' => 'No products available.'];
            return $this->response;
        }
        
        $this->response['data'] = $result;
        return $this->response;
    }
    
    public function getProduct(int $id): array
    {
        $result = $this->model->with('reviews')->where('id', $id)->first();
        
        if (empty($result)) {
            $this->response['code'] = 404;
            $this->response['data'] = ['message' => 'Product not found.'];
            return $this->response;
        }
        
        $this->response['data'] = $result;
        return $this->response;
    }
    
    public function createProduct(object $request): array
    {
        $validated = Validator::make($request->all(), $this->setProductValidationRules());
        
        if ($validated->fails()) {
            $this->response['code'] = 400;
            $this->response['data'] = $validated->errors();
            return $this->response;
        }
        
        $this->model->create([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
        ]);
        
        $this->response['data'] = array_intersect_key($request->all(), array_flip($this->model->getFillable()));
        return $this->response;
    }
    
    private function setProductValidationRules(bool $isCreate = true): array
    {
        return $isCreate ? [
            'name'        => 'required|string',
            'description' => 'required|string',
            'price'       => 'required|numeric'
        ] : [
            'name'        => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'price'       => 'sometimes|required|numeric'
        ];
    }
    
    public function updateProduct(object $request, int $id): array
    {
        $validated = Validator::make(
            array_merge($request->all(), ['id' => $id]),
            array_merge($this->setProductValidationRules(false), ['id' => 'exists:products,id']));
        
        if ($validated->fails()) {
            $this->response['code'] = 400;
            $this->response['data'] = $validated->errors();
            return $this->response;
        }
        
        $this->model->where('id', $id)->update($request->all());
        
        $this->response['data'] = array_intersect_key($request->all(), array_flip($this->model->getFillable()));
        return $this->response;
    }
    
    public function deleteProduct(int $id): array
    {
        $product = $this->model->find($id);
        
        if (!$product) {
            $this->response['code'] = 400;
            $this->response['data'] = ['product_id' => 'Product does not exists.'];
            return $this->response;
        }
        
        $product->delete();

        $this->response['code'] = 204; 
        return $this->response;
    }
}
