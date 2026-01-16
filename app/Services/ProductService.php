<?php


namespace App\Services;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductService {


    public function findProducts(Request $request){
        $perPage = $request->query("per_page", 10);
        $products = Product::with("category:id,name")->paginate($perPage, ["*"]);
        return $products;
    }

    public function findProductById(Int $id){
        $product = Product::with("category:id,name")->findOrFail($id);
        return $product;
    }

    public function findProductBySlug(String $slug){
        $product = Product::whereSlug($slug)->firstOrFail();
        return $product;
    }

    public function generateProductCode(): string
    {
        $today = Carbon::now();

        $day   = $today->format('d');
        $month = $today->format('m');
        $year  = $today->format('y');

        $prefix = 'PRD';

        $lastCode = Product::whereYear('created_at', $today->year)
            ->orderBy('product_code', 'desc')
            ->value('product_code');

        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, -2);
            $sequence = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $sequence = '01';
        }   

        return "{$prefix}-{$day}{$month}{$year}{$sequence}";
    }

    public function createProduct(array $data){
        $data['slug'] = Str::slug($data['name']);

        // LOGIKA BISNIS 2: Pengecekan Duplikat Slug (Manual handling)
        // Ini memastikan tidak ada error database jika slug sudah ada
        $exists = Product::where( 'slug', $data['slug'])->exists();

        $data['product_code'] = $this->generateProductCode();

        if($exists){
           throw new \Exception('Product name or slug already exists!', 409);
        }
  
        $createdProduct = Product::create($data);

        return $createdProduct;
    }


    public function updateProduct(int $id, array $data){
        $product = $this->findProductById($id);

        // cek request namenya ada ga, terus request namenya beda engga sama yg di database
        if(isset($data["name"]) && $data["name"] !== $product->name){
            // kalo ada dan namenya di request ketik yang baru berarti buat slug baru
            $newSlug = Str::slug($data["name"]);
            
            // Terus di cek di database berdasarkan slug, ada ga slug yg sama dengan yang kita masukan
            // Terus di cek juga di database berdasarkan id, ada ga id yang 
            $exists = Product::where("slug", operator: $newSlug)->where("id", "!=", $id)->exists();
            $data["slug"] = $exists ? $data["slug"] = $product->slug : $newSlug;
        }

        $product->update($data);
        return $product;
    }

    public function destroyProduct(int $id){
        $product = $this->findProductById($id);

        $product->delete();
        return $product;
    }
}