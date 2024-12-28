<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    /** @test */
    public function user_can_create_product_if_data_is_valid()
    {
        $dataCreated = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'price' => $this->faker->randomFloat(2, 100, 1000),
        ];

        $response = $this->json('POST', route('products.store'), $dataCreated);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(fn(AssertableJson $json) =>
            $json->has('data', fn(AssertableJson $json) =>
                $json->where('name', $dataCreated['name'])
                    ->etc())
                ->etc());

        $this->assertDatabaseHas('products', [
            'name' => $dataCreated['name'],
            'description' => $dataCreated['description'],
            'slug' => $dataCreated['slug'],
            'price' => $dataCreated['price'],
        ]);

    }

    /** @test */
    public function user_cannot_create_product_if_data_is_not_valid()
    {
        $dataCreated = [
            'name' => '',
            'description' => '',
            'slug' => '',
            'price' => '',
        ];

        $response = $this->json('POST', route('products.store'), $dataCreated);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name', 'description', 'slug', 'price']);
    }
}
