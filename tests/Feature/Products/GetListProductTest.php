<?php

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use function PHPUnit\Framework\assertJson;

class GetListProductTest extends TestCase
{

    /** @test */

    public function test_index_returns_products_list()
    {
        $productCount = Product::count();
        $response = $this->getJson(route('products.index'));
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn(AssertableJson $json) => $json->has('data', fn(AssertableJson $json) =>
            $json->has('data')
                ->has('links')
                ->has(
                    'meta',
                    fn(AssertableJson $json)
                    => $json->where('total', $productCount)
                        ->etc()
                ))->has('message'));
    }
}
