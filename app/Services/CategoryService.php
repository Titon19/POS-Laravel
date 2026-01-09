<?php

namespace App\Services;

use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryService {
    use ApiResponse;

    public function findCategories(Request $request){
        $perPage = $request->query("per_page", 10);
        $categories = Category::paginate($perPage, ["*"]);
        return $categories;
    }

    public function findCategoryById(Int $id){

        $category = Category::findOrFail($id);
        return $category;
    }

    public function findCategoryBySlug(String $slug){
        $category = Category::whereSlug($slug)->firstOrFail();
        return $category;
    }
    
    public function createCategory(array $data) 
    {
        // LOGIKA BISNIS 1: Generate Slug otomatis
        $data['slug'] = Str::slug($data['name']);

        // LOGIKA BISNIS 2: Pengecekan Duplikat Slug (Manual handling)
        // Ini memastikan tidak ada error database jika slug sudah ada
        $exists = Category::where( 'slug', $data['slug'])->exists();

        if($exists){
           throw new \Exception('Category name or slug already exists!', 409);
        }
  
        $createdCategory = Category::create($data);

        return $createdCategory;
    }


    public function updateCategory(int $id, array $data){
        $category = $this->findCategoryById($id);

        // cek request namenya ada ga, terus request namenya beda engga sama yg di database
        if(isset($data["name"]) && $data["name"] !== $category->name){
            // kalo ada dan namenya di request ketik yang baru berarti buat slug baru
            $newSlug = Str::slug($data["name"]);
            
            // Terus di cek di database berdasarkan slug, ada ga slug yg sama dengan yang kita masukan
            // Terus di cek juga di database berdasarkan id, ada ga id yang 
            $exists = Category::where("slug", $newSlug)->where("id", "!=", $id)->exists();
            $data["slug"] = $exists ? $data["slug"] = $category->slug : $newSlug;
        }

        $category->update($data);
        return $category;
    
    }

    public function destroyCategory(int $id){
        $category = $this->findCategoryById($id);

        $category->delete();
        return $category;
    }
}