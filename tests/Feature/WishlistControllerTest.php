<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class WishlistControllerTest extends TestCase
{
    //use RefreshDatabase;

     #[Test]
    public function it_adds_a_product_to_the_wishlist()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;
        $product = Product::factory()->create();


        // Act: Make a GET request to the products endpoint
        $response = $this->actingAs($user, 'api')->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/wishlist', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'user_id', 'product_id', 'created_at', 'updated_at',
            ]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

     #[Test]
    public function it_fails_to_add_a_product_with_invalid_data()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;

        $response = $this->actingAs($user, 'api')
        ->withHeaders(['Authorization' => "Bearer $token",])
        ->postJson('/api/wishlist', [
            'product_id' => 999, // Non-existent product ID
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

     #[Test]
    public function it_removes_a_product_from_the_wishlist()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;
        $wishlist = Wishlist::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')
        ->withHeaders(['Authorization' => "Bearer $token",])
        ->putJson('/api/wishlist', [
            'product_id' => $wishlist->product_id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Product removed from wishlist']);

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $wishlist->product_id,
        ]);
    }

     #[Test]
    public function it_fails_to_remove_a_nonexistent_product_from_the_wishlist()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;

        $response = $this->actingAs($user, 'api')
        ->withHeaders(['Authorization' => "Bearer $token",])
        ->putJson('/api/wishlist', [
            'product_id' => 999, // Non-existent product ID
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

     #[Test]
    public function it_clears_the_wishlist()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;
        Wishlist::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')
        ->withHeaders(['Authorization' => "Bearer $token",])
        ->deleteJson('/api/wishlist');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Wishlist cleared']);

        $this->assertDatabaseMissing('wishlists', ['user_id' => $user->id]);
    }

     #[Test]
    public function it_views_the_wishlist()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;
        $wishlist = Wishlist::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')
        ->withHeaders(['Authorization' => "Bearer $token",])
        ->getJson('/api/wishlist');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id', 'user_id', 'product_id', 'created_at', 'updated_at',
                ],
            ]);
    }

     #[Test]
    public function it_returns_no_items_when_wishlist_is_empty()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;

        $response = $this->actingAs($user, 'api')
        ->withHeaders(['Authorization' => "Bearer $token",])
        ->getJson('/api/wishlist');

        $response->assertStatus(404)
            ->assertJson(['message' => 'No items found in the wishlist']);
    }
}