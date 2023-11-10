<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_create_product_review_with_invalid_data()
    {
        // product id 999999999 is not existing
        // product rating is invalid. Should be between 1 to 10
        $response = $this->call('POST', '/api/products/999999999/reviews', [
            'user_name' => 'testUsername123',
            'rating'    => 25,
            'comment'   => 'test create invalid product review'         
        ]);
        
        $data = json_decode($response->getContent(), true);

        $response->assertStatus(400);
        $this->assertArrayHasKey('product_id', $data);
        $this->assertArrayHasKey('rating', $data);

    }
    
    public function test_create_product_review_with_valid_data()
    {
        $this->seed();
        
        $response = $this->call('POST', '/api/products/1/reviews', [
            'user_name' => 'testUsername123',
            'rating'    => 10,
            'comment'   => 'test create product review'         
        ]);
        
        $data = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertArrayHasKey('user_name', $data);
        $this->assertArrayHasKey('rating', $data);
        $this->assertArrayHasKey('comment', $data);
    }
}
