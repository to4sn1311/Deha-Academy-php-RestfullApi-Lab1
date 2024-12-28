<?php

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PostListProductTest extends TestCase
{
    /** @test */
    public function testGetListProductIfExists()
    {
        $product = Product::factory()->create();
        $response = $this->getJson(route('products.show', $product->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            fn(AssertableJson $json) =>
            $json->has('message')
                ->has(
                    'data',
                    fn(AssertableJson $json) =>
                    $json->where('name', $product->name)
                        ->etc()
                )
        );
    }

    public function testGetListProductIfNotExists()
    {
        $productId = -1;

        $response = $this->getJson(route('products.show', $productId));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
