<?php

use App\User;
use App\Brand;
use Illuminate\Http\Request;

Route::middleware('multiauth:brand')->group(function () {
    Route::get('/', function (Request $request) {
        return Brand::where('id', auth('brand')->id())->with('brand')->first();
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                     Start Kategori Product                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('product-kategori')->group(function () {
        Route::get('/', 'Referensi\BrandKategoriProdukController@index');
        Route::post('update-or-create', 'Referensi\BrandKategoriProdukController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandKategoriProdukController@destroy');
        Route::post('destroys', 'Referensi\BrandKategoriProdukController@destroys');
        Route::post('ishide', 'Referensi\BrandKategoriProdukController@hide');
        Route::post('reorder', 'Referensi\BrandKategoriProdukController@reorder');
        Route::get('get', 'Referensi\BrandKategoriProdukController@ajax');
        Route::post('plural', 'Referensi\BrandKategoriProdukController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                       End Kategori Product                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
});
