<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class ProductController extends Controller
{
    protected $product;

    /**
     * @param $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = $this->product->paginate(10);

        $productResource = ProductResource::collection($product)->response()->getData(true);
        $productCollection = new ProductCollection($product);

        return $this->sentSuccessResponse($productResource, 'Product retrieved', Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $dataCreate = $request->all();
        $product = $this->product->create($dataCreate);
        $productResource = new ProductResource($product);

        return $this->sentSuccessResponse($productResource, 'Product created', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->product->findOrFail($id);
        $productResource = new ProductResource($product);

        return $this->sentSuccessResponse($productResource, 'Product founded', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $product = $this->product->findOrFail($id);
        $dataUpdate = $request->all();
        $product->update($dataUpdate);
        $productResource = new ProductResource($product);
        return $this->sentSuccessResponse($productResource, 'Product updated', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->product->findOrFail($id);
        $product->delete();
        $productResource = new ProductResource($product);
        return $this->sentSuccessResponse($productResource, 'Product deleted', Response::HTTP_OK);
    }
}
