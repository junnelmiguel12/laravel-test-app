<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_fetch_products_with_no_available_products()
    {
        $this->refreshTestDatabase();
        
        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertSee('No products available.');
        $this->assertArrayHasKey('message', json_decode($response->getContent(), true));
    }
    
    public function test_fetch_products_with_available_products()
    {
        $this->seed();

        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertDontSee('No products available.');
        $this->assertNotEmpty(json_decode($response->getContent(), true));
    }
    
    public function test_fetch_a_non_existing_product()
    {
        $response = $this->get('/api/products/99999999');
        
        $response->assertStatus(404);
        $response->assertSee('Product not found.');
        $this->assertArrayHasKey('message', json_decode($response->getContent(), true));
    }
    
    public function test_fetch_an_existing_product()
    {
        $this->seed();
        
        $response = $this->get('/api/products/1');
        
        $data = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('price', $data);
    }
    
    public function test_create_product_with_invalid_data()
    {
        // product name and product description are not present in the request body
        // product price is invalid
        $response = $this->call('POST', '/api/products', [
            'price' => '123asd'         
        ]);
        
        $data = json_decode($response->getContent(), true);

        $response->assertStatus(400);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('price', $data);
    }
    
    public function test_create_product_with_valid_data()
    {
        $response = $this->call('POST', '/api/products', [
            'name'        => 'Test product',
            'description' => 'This is test product description',
            'price'       => 1500.5         
        ]);
        
        $data = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('price', $data);
    }
    
    public function test_update_product_with_invalida_data()
    {
        // product id 99999999 is not existing
        // product price is invalid
        $response = $this->call('PUT', '/api/products/99999999', [
            'description' => 'This is test product description',
            'price'       => '123asd'       
        ]);
        
        $data = json_decode($response->getContent(), true);
        
        $response->assertStatus(400);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('price', $data);
    }
    
    public function test_update_product_with_valid_data()
    {
        $this->seed();
        
        $response = $this->call('PUT', '/api/products/1', [
            'description' => 'Product 1 description is now updated.',
            'price'       => 5000       
        ]);
        
        $data = json_decode($response->getContent(), true);
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('price', $data);
    }
    
    public function test_delete_a_non_existing_product()
    {        
        $response = $this->call('DELETE', '/api/products/99999999');

        $response->assertStatus(400);
        $response->assertSee('Product does not exists.');
        $this->assertArrayHasKey('product_id', json_decode($response->getContent(), true));
    }
    
    public function test_delete_an_existing_product()
    {        
        $this->seed();
        
        $response = $this->call('DELETE', '/api/products/1');

        $response->assertStatus(204);
    }
}
