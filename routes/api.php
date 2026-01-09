<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





// Category
Route::prefix("categories")->group(function () {
    Route::get("/", [CategoryController::class, "index"])->name("categories.index");
    Route::post("/store", [CategoryController::class,"store"])->name("categories.store");
    Route::get("/{id}", [CategoryController::class,"show"])->name("categories.show");
    Route::get("/slug/{slug}", [CategoryController::class,"slug"])->name("categories.slug");
    Route::put("/update/{id}", [CategoryController::class, "update"])->name("categories.update");
    Route::delete("/destroy/{id}", [CategoryController::class, "destroy"])->name("categories.destroy");
});

// Products
Route::prefix("products")->group(function () {
    Route::get("/", [ProductController::class, "index"])->name("products.index");
    Route::post("/store", [ProductController::class,"store"])->name("products.store");
    Route::get("/{id}", [ProductController::class,"show"])->name("products.show");
    Route::get("/slug/{slug}", [ProductController::class,"slug"])->name("products.slug");
    Route::put("/update/{id}", [ProductController::class, "update"])->name("products.update");
    Route::delete("/destroy/{id}", [ProductController::class, "destroy"])->name("products.destroy");
});



