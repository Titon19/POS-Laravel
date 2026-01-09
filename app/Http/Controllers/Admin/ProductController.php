<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    protected $productService;

    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }

    public function index(Request $request){
          try {
            $products = $this->productService->findProducts($request);

            $paginated = $products->toArray();

            $data = $paginated['data'];

            $meta = [
                'current_page' => $paginated['current_page'],
                'last_page'    => $paginated['last_page'],
                'per_page'     => $paginated['per_page'],
                'total'        => $paginated['total'],
                'next_page_url' => $paginated['next_page_url'],
                'prev_page_url' => $paginated['prev_page_url'],
            ];

            return $this->successResponse($data, $meta, "Successfully get products");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to get products");
        }
    }

     public function store(StoreProductRequest $request) {

        try {
            $validateProduct = $this->productService->createProduct($request->validated());

            $newProduct = new ProductResource($validateProduct);
            return $this->successResponse($newProduct, null, "Successfully created product", 201);
        } catch (\Throwable $th) {
           return $this->errorResponse($th, "Failed to created product");
        }
    }

    public function show($id){
        try {
            $product = $this->productService->findProductById($id);

            return $this->successResponse($product, null, "Successfully show product");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to show product");
        }
    }

        public function slug($slug){
        try {
            $product = $this->productService->findProductBySlug($slug);

            return $this->successResponse($product, null, "Successfully show product");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to show product");
        }
    }


    public function update(UpdateProductRequest $request, $id){
        try {
            $validateProduct = $this->productService->updateProduct($id, $request->validated());
            $newProduct = new ProductResource($validateProduct);

            return $this->successResponse($newProduct, null, "Successfully updated product", 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to updated product");
        }
    }

    public function destroy($id){
        try {
            $deleteProduct = $this->productService->destroyProduct($id);
            return $this->successResponse($deleteProduct, null, "Successfully deleted product", 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to deleted product");
        }
    }
}
