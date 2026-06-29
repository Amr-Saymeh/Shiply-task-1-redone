<?php

namespace App\Http\Controllers\API;

use App\Constants\AuthConstants;
use App\Constants\ProductConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use Access;
    use HttpResponses;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(ProductResource::collection(Product::ForUser()->get()));
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $product = auth()->user()->products()->create($validated);
        if (!empty($validated['categories'] ?? [])) {            
            $categories = Category::ForUserByIds($validated['categories']);
            if (!$categories->isEmpty()) {
                $product->categories()->attach($categories);
            }
        }

        return $this->success(new ProductResource($product), ProductConstants::STORE);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        if (!$this->canAccess($product)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new ProductResource($product));
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        if (!$this->canAccess($product)) {
            return $this->error(AuthConstants::PERMISSION);
        }
        $validated = $request->validated();
        if (isset($validated['categories'])) {
            $categories = Category::ForUserByIds($validated['categories']);

            $product->categories()->detach();
            if (!$categories->isEmpty()) {
                $product->categories()->attach($categories);
            }
        }

        $product->update($validated);

        return $this->success(new ProductResource($product), ProductConstants::UPDATE);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        if (!$this->canAccess($product)) {
            return $this->error(AuthConstants::PERMISSION);
        }

        $product->delete();

        return $this->success([], ProductConstants::DESTROY);
    }
    public function search(Request $request): JsonResponse
    {
        $name = trim($request->query('name', ''));

        if ($name === '') {
            return $this->success(ProductResource::collection(collect()));
        }

        $products = Product::ForUser()->where('name', 'LIKE', "%{$name}%")->get();

        return response()->json(ProductResource::collection($products));
    }
}
