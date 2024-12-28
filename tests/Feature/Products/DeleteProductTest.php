<?php

use Tests\TestCase;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;


class DeleteProductTest extends TestCase
{
    /** @test */
    public function testDeleteProductIfExists()
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson(route('products.destroy', $product->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Product deleted']);
    }

    /** @test */
    public function testDeleteProductIfNotExists()
    {
        $nonExistentProductId = 999;
        $response = $this->deleteJson(route('products.destroy', $nonExistentProductId));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['message' => 'No query results for model [App\\Models\\Product] 999']);
    }
}
