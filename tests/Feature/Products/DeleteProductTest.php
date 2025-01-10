<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class DeleteProductTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function testDeleteProductIfExists()
    {
        $this->actingAs($this->user);
        $product = Product::factory()->create();
        $response = $this->deleteJson(route('products.destroy', $product->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Product deleted']);
    }

    /** @test */
    public function testDeleteProductIfNotExists()
    {
        $this->actingAs($this->user);
        $nonExistentProductId = 999;
        $response = $this->deleteJson(route('products.destroy', $nonExistentProductId));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['message' => 'No query results for model [App\\Models\\Product] 999']);
    }

    /** @test */
    public function testDeleteProductUnauthenticated()
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson(route('products.destroy', $product->id));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
