<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
       $this->categoryService = $categoryService;
    }

    public function index(Request $request) {
        try {
            $categories = $this->categoryService->findCategories($request);

            $paginated = $categories->toArray();

            $data = $paginated['data'];

            $meta = [
                'current_page' => $paginated['current_page'],
                'last_page'    => $paginated['last_page'],
                'per_page'     => $paginated['per_page'],
                'total'        => $paginated['total'],
                'next_page_url' => $paginated['next_page_url'],
                'prev_page_url' => $paginated['prev_page_url'],
            ];

            return $this->successResponse($data, $meta, "Successfully get categories");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to get categories");
        }
    }


    public function store(StoreCategoryRequest $request) {
        try {
            $validateCategory = $this->categoryService->createCategory($request->validated());

            $newCategory = new CategoryResource($validateCategory);
            return $this->successResponse($newCategory, null, "Successfully created category", 201);
        } catch (\Throwable $th) {
           return $this->errorResponse($th, "Failed to created category");
        }

    }

    public function show($id){
        try {
            $category = $this->categoryService->findCategoryById($id);

            return $this->successResponse($category, null, "Successfully show category");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to show category");
        }
    }
    public function slug($slug){
        try {
            $category = $this->categoryService->findCategoryBySlug($slug);

            return $this->successResponse($category, null, "Successfully show category");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to show category");
        }
    }


    public function update(UpdateCategoryRequest $request, $id){
        try {
            $validateCategory = $this->categoryService->updateCategory($id, $request->validated());
            $newCategory = new CategoryResource($validateCategory);
            return $this->successResponse($newCategory, null, "Successfully updated category", 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to updated category");
        }
    }

    public function destroy($id){
        try {
            $deleteCategory = $this->categoryService->destroyCategory($id);
            return $this->successResponse($deleteCategory, null, "Successfully deleted category", 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to deleted category");
        }
    }
}
