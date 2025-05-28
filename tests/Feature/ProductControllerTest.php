<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProductControllerTest extends TestCase
{
    //use RefreshDatabase;

     #[Test]
    public function it_fetches_all_products_successfully()
    {
        // Arrange: Create some products
        Product::factory()->count(3)->create();

        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;

        // Act: Make a GET request to the products endpoint
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/products');

        // Assert: Check the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    //'stock',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

}