<?php

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_update_products_if_exists_and_data_is_valid()
    {
        $product = Product::factory()->create();

        $response = $this->putJson(route('products.update', $product), [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'slug' => 'updated-product-name',
            'price' => 99.99,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'slug' => 'updated-product-name',
            'price' => 99.99,
        ]);
    }

    /** @test */
    public function user_cannot_update_product_if_it_does_not_exist()
    {
        $response = $this->putJson(route('products.update', 999), [
            'name' => 'Non-existent Product',
            'description' => 'Non-existent Product Description',
            'slug' => 'non-existent-product',
            'price' => 99.99,
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function user_cannot_update_product_with_invalid_data()
    {
        $product = Product::factory()->create();

        $response = $this->putJson(route('products.update', $product), [
            'name' => '',
            'price' => 'invalid-price',
            'description' => '',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'name' => '',
            'price' => 'invalid-price',
            'description' => '',
        ]);
    }
}
