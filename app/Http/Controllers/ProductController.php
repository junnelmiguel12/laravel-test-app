<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function __construct(Product $model)
    {
        $this->service = new ProductService($model);
    }
    
    public function showAllProducts()
    {
        $result = $this->service->showAllProducts();
        return response()->json($result['data'], $result['code']);
    }
    
    public function getProduct($id)
    {
        $result = $this->service->getProduct($id);
        return response()->json($result['data'], $result['code']);
    }
    
    public function createProduct(Request $request)
    {
        $result = $this->service->createProduct($request);
        return response()->json($result['data'], $result['code']);
    }
    
    public function updateProduct(Request $request, $id)
    {
        $result = $this->service->updateProduct($request, $id);
        return response()->json($result['data'], $result['code']);
    }
    
    public function deleteProduct($id)
    {
        $result = $this->service->deleteProduct($id);
        return response()->json($result['data'], $result['code']);
    }
}
