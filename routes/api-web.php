<?php

use App\User;
use Illuminate\Http\Request;

Route::get('/test-email', 'Referensi\SubscribeController@testEmail');
Route::prefix('email')->group(function () {
    Route::post('confirm', 'Mail\ConfirmMailController@confirm');
    Route::post('reject', 'Mail\ConfirmMailController@reject');
});
Route::post('/image/plural', 'Transaksi\ImageController@upload');
Route::post('/brand-image/plural/{brand_id}', 'Referensi\BrandImageController@upload');
Route::middleware('multiauth:admin,brand')->group(function () {
    Route::prefix('admins')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user('admin');
        });
        Route::get('ubah-password', 'UserController@gantiPassword');
        Route::post('kelola', 'UserController@kelola');
    });
    Route::prefix('berita')->group(function () {
        Route::get('/', 'Transaksi\BeritaController@index');
        Route::post('update-or-create', 'Transaksi\BeritaController@updateOrCreate');
        Route::post('destroy', 'Transaksi\BeritaController@destroy');
        Route::post('destroys', 'Transaksi\BeritaController@destroys');
        Route::post('ishide', 'Transaksi\BeritaController@hide');
        Route::post('publish', 'Transaksi\BeritaController@publish');
        Route::post('reorder', 'Transaksi\BeritaController@reorder');
        Route::get('get', 'Transaksi\BeritaController@ajax');
        Route::post('get-id', 'Transaksi\BeritaController@ajaxId');
        Route::post('socmed', 'Transaksi\BeritaController@socmed');
    });
    Route::prefix('kategori-berita')->group(function () {
        Route::get('/', 'Referensi\KategoriController@index');
        Route::post('update-or-create', 'Referensi\KategoriController@updateOrCreate');
        Route::post('destroy', 'Referensi\KategoriController@destroy');
        Route::post('destroys', 'Referensi\KategoriController@destroys');
        Route::post('ishide', 'Referensi\KategoriController@hide');
        Route::post('reorder', 'Referensi\KategoriController@reorder');
        Route::get('get', 'Referensi\KategoriController@ajax');
    });
    Route::prefix('kategori-gallery')->group(function () {
        Route::get('/', 'Referensi\GalleryKategoriController@index');
        Route::post('update-or-create', 'Referensi\GalleryKategoriController@updateOrCreate');
        Route::post('destroy', 'Referensi\GalleryKategoriController@destroy');
        Route::post('destroys', 'Referensi\GalleryKategoriController@destroys');
        Route::post('ishide', 'Referensi\GalleryKategoriController@hide');
        Route::post('reorder', 'Referensi\GalleryKategoriController@reorder');
        Route::get('get', 'Referensi\GalleryKategoriController@ajax');
    });
    Route::prefix('mitra')->group(function () {
        Route::get('/', 'Referensi\MitraController@index');
        Route::post('update-or-create', 'Referensi\MitraController@updateOrCreate');
        Route::post('destroy', 'Referensi\MitraController@destroy');
        Route::post('destroys', 'Referensi\MitraController@destroys');
        Route::post('ishide', 'Referensi\MitraController@hide');
        Route::post('reorder', 'Referensi\MitraController@reorder');
        Route::get('get', 'Referensi\MitraController@ajax');
    });
    Route::prefix('folder')->group(function () {
        Route::get('/', 'Referensi\FolderController@index');
        Route::post('update-or-create', 'Referensi\FolderController@updateOrCreate');
        Route::post('destroy', 'Referensi\FolderController@destroy');
        Route::post('destroys', 'Referensi\FolderController@destroys');
        Route::post('ishide', 'Referensi\FolderController@hide');
        Route::post('reorder', 'Referensi\FolderController@reorder');
        Route::get('get', 'Referensi\FolderController@ajax');
    });
    Route::prefix('subscribe')->group(function () {
        Route::get('/', 'Referensi\subscribeController@index');
        Route::post('update-or-create', 'Referensi\subscribeController@updateOrCreate');
        Route::post('destroy', 'Referensi\subscribeController@destroy');
        Route::post('destroys', 'Referensi\subscribeController@destroys');
        Route::post('ishide', 'Referensi\subscribeController@hide');
        Route::post('reorder', 'Referensi\subscribeController@reorder');
        Route::get('get', 'Referensi\subscribeController@ajax');
    });
    Route::prefix('broadcast')->group(function () {
        Route::get('/', 'Referensi\BroadcastController@index');
        Route::post('update-or-create', 'Referensi\BroadcastController@updateOrCreate');
        Route::post('destroy', 'Referensi\BroadcastController@destroy');
        Route::post('destroys', 'Referensi\BroadcastController@destroys');
        Route::post('ishide', 'Referensi\BroadcastController@hide');
        Route::post('reorder', 'Referensi\BroadcastController@reorder');
        Route::get('get', 'Referensi\BroadcastController@ajax');
    });
    Route::prefix('iklan')->group(function () {
        Route::get('/', 'Referensi\iklanController@index');
        Route::post('update-or-create', 'Referensi\iklanController@updateOrCreate');
        Route::post('destroy', 'Referensi\iklanController@destroy');
        Route::post('destroys', 'Referensi\iklanController@destroys');
        Route::post('ishide', 'Referensi\iklanController@hide');
        Route::post('reorder', 'Referensi\iklanController@reorder');
        Route::get('get', 'Referensi\iklanController@ajax');
    });
    Route::prefix('quotes')->group(function () {
        Route::get('/', 'Referensi\quotesController@index');
        Route::post('update-or-create', 'Referensi\quotesController@updateOrCreate');
        Route::post('destroy', 'Referensi\quotesController@destroy');
        Route::post('destroys', 'Referensi\quotesController@destroys');
        Route::post('ishide', 'Referensi\quotesController@hide');
        Route::post('reorder', 'Referensi\quotesController@reorder');
        Route::get('get', 'Referensi\quotesController@ajax');
    });
    Route::prefix('image')->group(function () {
        Route::get('/', 'Transaksi\ImageController@index');
        Route::post('update-or-create', 'Transaksi\ImageController@updateOrCreate');
        Route::post('destroy', 'Transaksi\ImageController@destroy');
        Route::post('destroys', 'Transaksi\ImageController@destroys');
        Route::post('ishide', 'Transaksi\ImageController@hide');
        Route::post('reorder', 'Transaksi\ImageController@reorder');
        Route::get('get', 'Transaksi\ImageController@ajax');
        Route::get('only', 'Transaksi\ImageController@onlyImage');
        // Route::post('plural', 'Transaksi\ImageController@upload');
    });
    Route::prefix('halamans')->group(function () {
        Route::get('get', 'Referensi\PageController@custom');
    });
    Route::prefix('halaman')->group(function () {
        Route::get('/', 'Referensi\PageController@index');
        Route::post('update-or-create', 'Referensi\PageController@updateOrCreate');
        Route::post('destroy', 'Referensi\PageController@destroy');
        Route::post('destroys', 'Referensi\PageController@destroys');
        Route::post('ishide', 'Referensi\PageController@hide');
        Route::post('reorder', 'Referensi\PageController@reorder');
        Route::get('get', 'Referensi\PageController@ajax');
        Route::post('plural', 'Referensi\PageController@upload');
    });
    Route::prefix('setting-beranda')->group(function () {
        Route::get('/', 'Referensi\BlokController@index');
        Route::post('update-or-create', 'Referensi\BlokController@updateOrCreate');
        Route::post('destroy', 'Referensi\BlokController@destroy');
        Route::post('destroys', 'Referensi\BlokController@destroys');
        Route::post('ishide', 'Referensi\BlokController@hide');
        Route::post('reorder', 'Referensi\BlokController@reorder');
        Route::get('get', 'Referensi\BlokController@ajax');
        Route::post('plural', 'Referensi\BlokController@upload');
    });
    Route::prefix('gallery')->group(function () {
        Route::get('/', 'Transaksi\GalleryController@index');
        Route::post('update-or-create', 'Transaksi\GalleryController@updateOrCreate');
        Route::post('destroy', 'Transaksi\GalleryController@destroy');
        Route::post('destroys', 'Transaksi\GalleryController@destroys');
        Route::post('ishide', 'Transaksi\GalleryController@hide');
        Route::post('reorder', 'Transaksi\GalleryController@reorder');
        Route::get('get', 'Transaksi\GalleryController@ajax');
        Route::post('get-id', 'Transaksi\GalleryController@ajaxId');
        Route::post('plural', 'Transaksi\GalleryController@upload');
    });
    Route::prefix('menu')->group(function () {
        Route::get('/', 'Referensi\MenuController@index');
        Route::post('update-or-create', 'Referensi\MenuController@updateOrCreate');
        Route::post('destroy', 'Referensi\MenuController@destroy');
        Route::post('destroys', 'Referensi\MenuController@destroys');
        Route::post('ishide', 'Referensi\MenuController@hide');
		Route::post('ishide-footer', 'Referensi\MenuController@hideFooter');
        Route::post('reorder', 'Referensi\MenuController@reorder');
        Route::get('get', 'Referensi\MenuController@ajax');
        Route::post('plural', 'Referensi\MenuController@upload');
    });
    Route::prefix('menu-sub')->group(function () {
        Route::get('/', 'Referensi\MenuSubController@index');
        Route::post('update-or-create', 'Referensi\MenuSubController@updateOrCreate');
        Route::post('destroy', 'Referensi\MenuSubController@destroy');
        Route::post('destroys', 'Referensi\MenuSubController@destroys');
        Route::post('ishide', 'Referensi\MenuSubController@hide');
        Route::post('reorder', 'Referensi\MenuSubController@reorder');
        Route::get('get', 'Referensi\MenuSubController@ajax');
        Route::post('plural', 'Referensi\MenuSubController@upload');
    });
    Route::prefix('slider')->group(function () {
        Route::get('/', 'Referensi\SliderController@index');
        Route::post('update-or-create', 'Referensi\SliderController@updateOrCreate');
        Route::post('destroy', 'Referensi\SliderController@destroy');
        Route::post('destroys', 'Referensi\SliderController@destroys');
        Route::post('ishide', 'Referensi\SliderController@hide');
        Route::post('reorder', 'Referensi\SliderController@reorder');
        Route::get('get', 'Referensi\SliderController@ajax');
        Route::post('get-id', 'Referensi\SliderController@ajaxId');
        Route::post('plural', 'Referensi\SliderController@upload');
    });
    Route::prefix('kontak')->group(function () {
        Route::get('/', 'Referensi\ContactController@index');
        Route::post('update-or-create', 'Referensi\ContactController@updateOrCreate');
        Route::post('destroy', 'Referensi\ContactController@destroy');
        Route::post('destroys', 'Referensi\ContactController@destroys');
        Route::post('ishide', 'Referensi\ContactController@hide');
        Route::post('reorder', 'Referensi\ContactController@reorder');
        Route::get('get', 'Referensi\ContactController@ajax');
        Route::post('plural', 'Referensi\ContactController@upload');
    });

    Route::prefix('rated')->group(function () {
        Route::get('/', 'Transaksi\RatedController@index');
        Route::post('ishide', 'Transaksi\RatedController@hide');
        Route::post('sum-avg', 'Transaksi\RatedController@sumAvg');
    });


    Route::prefix('setting-layout')->group(function () {
        Route::get('/', 'Referensi\BlokTemplateController@index');
        Route::post('update-or-create', 'Referensi\BlokTemplateController@updateOrCreate');
        Route::post('destroy', 'Referensi\BlokTemplateController@destroy');
        Route::post('destroys', 'Referensi\BlokTemplateController@destroys');
        Route::post('ishide', 'Referensi\BlokTemplateController@hide');
        Route::post('reorder', 'Referensi\BlokTemplateController@reorder');
        Route::get('get', 'Referensi\BlokTemplateController@ajax');
        Route::post('plural', 'Referensi\BlokTemplateController@upload');
    });

    Route::prefix('general')->group(function () {
        Route::get('/', 'Referensi\GeneralController@index');
        Route::post('update-or-create', 'Referensi\GeneralController@updateOrCreate');
        Route::post('destroy', 'Referensi\GeneralController@destroy');
        Route::post('destroys', 'Referensi\GeneralController@destroys');
        Route::post('ishide', 'Referensi\GeneralController@hide');
        Route::post('reorder', 'Referensi\GeneralController@reorder');
        Route::get('get', 'Referensi\GeneralController@ajax');
        Route::post('plural', 'Referensi\GeneralController@upload');
    });

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                         Start Daftar Brand                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand')->group(function () {
        Route::get('/', 'Referensi\BrandController@index');
        Route::post('update-or-create', 'Referensi\BrandController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandController@destroy');
        Route::post('destroys', 'Referensi\BrandController@destroys');
        Route::post('ishide', 'Referensi\BrandController@hide');
        Route::post('reorder', 'Referensi\BrandController@reorder');
        Route::get('get', 'Referensi\BrandController@ajax');
        Route::post('get-id', 'Referensi\BrandController@ajaxId');
        Route::get('get-uuid', 'Referensi\BrandController@ajaxUuid');
        Route::post('plural', 'Referensi\BrandController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                           End Daftar Brand                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                     Start Brand Kategori                            +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-kategori')->group(function () {
        Route::get('/', 'Referensi\BrandKategoriController@index');
        Route::post('update-or-create', 'Referensi\BrandKategoriController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandKategoriController@destroy');
        Route::post('destroys', 'Referensi\BrandKategoriController@destroys');
        Route::post('ishide', 'Referensi\BrandKategoriController@hide');
        Route::post('reorder', 'Referensi\BrandKategoriController@reorder');
        Route::get('get', 'Referensi\BrandKategoriController@ajax');
        Route::post('plural', 'Referensi\BrandKategoriController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                       End Brand Kategori                            +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                        Start Brand Image                            +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-image')->group(function () {
        Route::get('/', 'Referensi\BrandImageController@index');
        Route::post('update-or-create', 'Referensi\BrandImageController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandImageController@destroy');
        Route::post('destroys', 'Referensi\BrandImageController@destroys');
        Route::post('ishide', 'Referensi\BrandImageController@hide');
        Route::post('reorder', 'Referensi\BrandImageController@reorder');
        Route::get('get', 'Referensi\BrandImageController@ajax');
        Route::get('only', 'Referensi\BrandImageController@onlyImage');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                          End Brand Image                            +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                        Start Daftar Investor                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('investor')->group(function () {
        Route::get('/', 'Referensi\InvestorController@index');
        Route::post('update-or-create', 'Referensi\InvestorController@updateOrCreate');
        Route::post('destroy', 'Referensi\InvestorController@destroy');
        Route::post('destroys', 'Referensi\InvestorController@destroys');
        Route::post('ishide', 'Referensi\InvestorController@hide');
        Route::post('reorder', 'Referensi\InvestorController@reorder');
        Route::get('get', 'Referensi\InvestorController@ajax');
        Route::post('plural', 'Referensi\InvestorController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                          End Daftar Investor                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                     Start Vacancy                                   +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('vacancy')->group(function () {
        Route::get('/', 'Transaksi\VacancyController@index');
        Route::post('update-or-create', 'Transaksi\VacancyController@updateOrCreate');
        Route::post('destroy', 'Transaksi\VacancyController@destroy');
        Route::post('destroys', 'Transaksi\VacancyController@destroys');
        Route::post('ishide', 'Transaksi\VacancyController@hide');
        Route::post('reorder', 'Transaksi\VacancyController@reorder');
        Route::get('get', 'Transaksi\VacancyController@ajax');
        Route::post('get-id', 'Transaksi\VacancyController@ajaxId');
        Route::post('plural', 'Transaksi\VacancyController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                       End Vacancy                                   +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                     Start Vacancy Kategori                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('vacancy-kategori')->group(function () {
        Route::get('/', 'Referensi\KategoriVacancyController@index');
        Route::post('update-or-create', 'Referensi\KategoriVacancyController@updateOrCreate');
        Route::post('destroy', 'Referensi\KategoriVacancyController@destroy');
        Route::post('destroys', 'Referensi\KategoriVacancyController@destroys');
        Route::post('ishide', 'Referensi\KategoriVacancyController@hide');
        Route::post('reorder', 'Referensi\KategoriVacancyController@reorder');
        Route::get('get', 'Referensi\KategoriVacancyController@ajax');
        Route::post('plural', 'Referensi\KategoriVacancyController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                       End Vacancy Kategori                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                     Start Vacancy Sub Kategori                      +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('vacancy-sub-kategori')->group(function () {
        Route::get('/', 'Referensi\KategoriSubVacancyController@index');
        Route::post('update-or-create', 'Referensi\KategoriSubVacancyController@updateOrCreate');
        Route::post('destroy', 'Referensi\KategoriSubVacancyController@destroy');
        Route::post('destroys', 'Referensi\KategoriSubVacancyController@destroys');
        Route::post('ishide', 'Referensi\KategoriSubVacancyController@hide');
        Route::post('reorder', 'Referensi\KategoriSubVacancyController@reorder');
        Route::get('get', 'Referensi\KategoriSubVacancyController@ajax');
        Route::post('get-id', 'Referensi\KategoriSubVacancyController@ajaxId');
        Route::post('plural', 'Referensi\KategoriSubVacancyController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                       End Vacancy Sub Kategori                      +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                         Start Warna                                 +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('warna')->group(function () {
        Route::get('/', 'Referensi\WarnaController@index');
        Route::post('update-or-create', 'Referensi\WarnaController@updateOrCreate');
        Route::post('destroy', 'Referensi\WarnaController@destroy');
        Route::post('destroys', 'Referensi\WarnaController@destroys');
        Route::post('ishide', 'Referensi\WarnaController@hide');
        Route::post('reorder', 'Referensi\WarnaController@reorder');
        Route::get('get', 'Referensi\WarnaController@ajax');
        Route::post('get-id', 'Referensi\WarnaController@ajaxId');
        Route::post('plural', 'Referensi\WarnaController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                       End Warna                                     +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                         Start Warna Detail                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('warna-detail')->group(function () {
        Route::get('/', 'Transaksi\WarnaDetailController@index');
        Route::post('update-or-create', 'Transaksi\WarnaDetailController@updateOrCreate');
        Route::post('destroy', 'Transaksi\WarnaDetailController@destroy');
        Route::post('destroys', 'Transaksi\WarnaDetailController@destroys');
        Route::post('ishide', 'Transaksi\WarnaDetailController@hide');
        Route::post('reorder', 'Transaksi\WarnaDetailController@reorder');
        Route::get('get', 'Transaksi\WarnaDetailController@ajax');
        Route::post('get-id', 'Transaksi\WarnaDetailController@ajaxId');
        Route::post('plural', 'Transaksi\WarnaDetailController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                       End Warna Detail                              +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    Route::prefix('bank')->group(function () {
        Route::get('/', 'Referensi\BankController@index');
        Route::post('update-or-create', 'Referensi\BankController@updateOrCreate');
        Route::post('destroy', 'Referensi\BankController@destroy');
        Route::post('destroys', 'Referensi\BankController@destroys');
        Route::post('ishide', 'Referensi\BankController@hide');
        Route::post('reorder', 'Referensi\BankController@reorder');
        Route::get('get', 'Referensi\BankController@ajax');
    });
    Route::prefix('rekening-bank')->group(function () {
        Route::get('/', 'Referensi\RekeningBankController@index');
        Route::post('update-or-create', 'Referensi\RekeningBankController@updateOrCreate');
        Route::post('destroy', 'Referensi\RekeningBankController@destroy');
        Route::post('destroys', 'Referensi\RekeningBankController@destroys');
        Route::post('ishide', 'Referensi\RekeningBankController@hide');
        Route::post('reorder', 'Referensi\RekeningBankController@reorder');
        Route::get('get', 'Referensi\RekeningBankController@ajax');
    });
    Route::prefix('quotes')->group(function () {
        Route::get('/', 'Referensi\QuotesController@index');
        Route::post('update-or-create', 'Referensi\QuotesController@updateOrCreate');
        Route::post('destroy', 'Referensi\QuotesController@destroy');
        Route::post('destroys', 'Referensi\QuotesController@destroys');
        Route::post('ishide', 'Referensi\QuotesController@hide');
        Route::post('reorder', 'Referensi\QuotesController@reorder');
        Route::get('get', 'Referensi\QuotesController@ajax');
    });
    Route::prefix('ticker')->group(function () {
        Route::get('/', 'Referensi\TickerController@index');
        Route::post('update-or-create', 'Referensi\TickerController@updateOrCreate');
        Route::post('destroy', 'Referensi\TickerController@destroy');
        Route::post('destroys', 'Referensi\TickerController@destroys');
        Route::post('ishide', 'Referensi\TickerController@hide');
        Route::post('reorder', 'Referensi\TickerController@reorder');
        Route::get('get', 'Referensi\TickerController@ajax');
    });
    Route::prefix('ruangan')->group(function () {
        Route::get('/', 'Referensi\RuanganController@index');
        Route::post('update-or-create', 'Referensi\RuanganController@updateOrCreate');
        Route::post('destroy', 'Referensi\RuanganController@destroy');
        Route::post('destroys', 'Referensi\RuanganController@destroys');
        Route::post('ishide', 'Referensi\RuanganController@hide');
        Route::post('reorder', 'Referensi\RuanganController@reorder');
        Route::get('get', 'Referensi\RuanganController@ajax');
    });
    Route::prefix('imam')->group(function () {
        Route::get('/', 'Referensi\ImamController@index');
        Route::post('update-or-create', 'Referensi\ImamController@updateOrCreate');
        Route::post('destroy', 'Referensi\ImamController@destroy');
        Route::post('destroys', 'Referensi\ImamController@destroys');
        Route::post('ishide', 'Referensi\ImamController@hide');
        Route::post('reorder', 'Referensi\ImamController@reorder');
        Route::get('get', 'Referensi\ImamController@ajax');
    });
    Route::prefix('setting-booking')->group(function () {
        Route::get('/', 'Referensi\SettingBookingController@index');
        Route::post('update-or-create', 'Referensi\SettingBookingController@updateOrCreate');
        Route::post('destroy', 'Referensi\SettingBookingController@destroy');
        Route::post('destroys', 'Referensi\SettingBookingController@destroys');
        Route::post('ishide', 'Referensi\SettingBookingController@hide');
        Route::post('reorder', 'Referensi\SettingBookingController@reorder');
        Route::get('get', 'Referensi\SettingBookingController@ajax');
    });
    Route::prefix('agenda')->group(function () {
        Route::get('/', 'Referensi\AgendaController@index');
        Route::post('update-or-create', 'Referensi\AgendaController@updateOrCreate');
        Route::post('destroy', 'Referensi\AgendaController@destroy');
        Route::post('destroys', 'Referensi\AgendaController@destroys');
        Route::post('ishide', 'Referensi\AgendaController@hide');
        Route::post('reorder', 'Referensi\AgendaController@reorder');
        Route::get('get', 'Referensi\AgendaController@ajax');
    });
    Route::prefix('agenda-detail')->group(function () {
        Route::get('/', 'Referensi\AgendaDetailController@index');
        Route::post('update-or-create', 'Referensi\AgendaDetailController@updateOrCreate');
        Route::post('destroy', 'Referensi\AgendaDetailController@destroy');
        Route::post('destroys', 'Referensi\AgendaDetailController@destroys');
        Route::post('ishide', 'Referensi\AgendaDetailController@hide');
        Route::post('reorder', 'Referensi\AgendaDetailController@reorder');
        Route::get('get', 'Referensi\AgendaDetailController@ajax');
    });
    Route::prefix('type-infaq')->group(function () {
        Route::get('/', 'Referensi\typeInfaqController@index');
        Route::post('update-or-create', 'Referensi\typeInfaqController@updateOrCreate');
        Route::post('destroy', 'Referensi\typeInfaqController@destroy');
        Route::post('destroys', 'Referensi\typeInfaqController@destroys');
        Route::post('ishide', 'Referensi\typeInfaqController@hide');
        Route::post('reorder', 'Referensi\typeInfaqController@reorder');
        Route::get('get', 'Referensi\typeInfaqController@ajax');
    });
    Route::prefix('infaq')->group(function () {
        Route::get('/', 'Transaksi\InfaqController@index');
        Route::post('update-or-create', 'Transaksi\InfaqController@updateOrCreate');
        Route::post('destroy', 'Transaksi\InfaqController@destroy');
        Route::post('destroys', 'Transaksi\InfaqController@destroys');
        Route::post('ishide', 'Transaksi\InfaqController@hide');
        Route::post('reorder', 'Transaksi\InfaqController@reorder');
        Route::get('get', 'Transaksi\InfaqController@ajax');
    });
    Route::prefix('inventaris')->group(function () {
        Route::get('/', 'Referensi\InventarisController@index');
        Route::post('update-or-create', 'Referensi\InventarisController@updateOrCreate');
        Route::post('destroy', 'Referensi\InventarisController@destroy');
        Route::post('destroys', 'Referensi\InventarisController@destroys');
        Route::post('ishide', 'Referensi\InventarisController@hide');
        Route::post('reorder', 'Referensi\InventarisController@reorder');
        Route::get('get', 'Referensi\InventarisController@ajax');
    });
    Route::prefix('jenis-inventaris')->group(function () {
        Route::get('/', 'Referensi\JenisInventarisController@index');
        Route::post('update-or-create', 'Referensi\JenisInventarisController@updateOrCreate');
        Route::post('destroy', 'Referensi\JenisInventarisController@destroy');
        Route::post('destroys', 'Referensi\JenisInventarisController@destroys');
        Route::post('ishide', 'Referensi\JenisInventarisController@hide');
        Route::post('reorder', 'Referensi\JenisInventarisController@reorder');
        Route::get('get', 'Referensi\JenisInventarisController@ajax');
    });
    Route::prefix('alumni')->group(function () {
        Route::get('/', 'Referensi\AlumniController@index');
        Route::post('update-or-create', 'Referensi\AlumniController@updateOrCreate');
        Route::post('destroy', 'Referensi\AlumniController@destroy');
        Route::post('destroys', 'Referensi\AlumniController@destroys');
        Route::post('ishide', 'Referensi\AlumniController@hide');
        Route::post('reorder', 'Referensi\AlumniController@reorder');
        Route::get('get', 'Referensi\AlumniController@ajax');
    });
    Route::prefix('divisi')->group(function () {
        Route::get('/', 'Referensi\DivisiController@index');
        Route::post('update-or-create', 'Referensi\DivisiController@updateOrCreate');
        Route::post('destroy', 'Referensi\DivisiController@destroy');
        Route::post('destroys', 'Referensi\DivisiController@destroys');
        Route::post('ishide', 'Referensi\DivisiController@hide');
        Route::post('reorder', 'Referensi\DivisiController@reorder');
        Route::get('get', 'Referensi\DivisiController@ajax');
    });
    Route::prefix('jabatan')->group(function () {
        Route::get('/', 'Referensi\JabatanController@index');
        Route::post('update-or-create', 'Referensi\JabatanController@updateOrCreate');
        Route::post('destroy', 'Referensi\JabatanController@destroy');
        Route::post('destroys', 'Referensi\JabatanController@destroys');
        Route::post('ishide', 'Referensi\JabatanController@hide');
        Route::post('reorder', 'Referensi\JabatanController@reorder');
        Route::get('get', 'Referensi\JabatanController@ajax');
    });
    Route::prefix('pegawai')->group(function () {
        Route::get('/', 'Referensi\PegawaiController@index');
        Route::post('update-or-create', 'Referensi\PegawaiController@updateOrCreate');
        Route::post('destroy', 'Referensi\PegawaiController@destroy');
        Route::post('destroys', 'Referensi\PegawaiController@destroys');
        Route::post('ishide', 'Referensi\PegawaiController@hide');
        Route::post('reorder', 'Referensi\PegawaiController@reorder');
        Route::get('get', 'Referensi\PegawaiController@ajax');
    });
    Route::prefix('pegawai-detail')->group(function () {
        Route::get('/', 'Referensi\PegawaiDetailController@index');
        Route::post('update-or-create', 'Referensi\PegawaiDetailController@updateOrCreate');
        Route::post('destroy', 'Referensi\PegawaiDetailController@destroy');
        Route::post('destroys', 'Referensi\PegawaiDetailController@destroys');
        Route::post('ishide', 'Referensi\PegawaiDetailController@hide');
        Route::post('reorder', 'Referensi\PegawaiDetailController@reorder');
        Route::get('get', 'Referensi\PegawaiDetailController@ajax');
    });
    Route::prefix('siswa')->group(function () {
        Route::get('/', 'Referensi\SiswaController@index');
        Route::post('update-or-create', 'Referensi\SiswaController@updateOrCreate');
        Route::post('destroy', 'Referensi\SiswaController@destroy');
        Route::post('destroys', 'Referensi\SiswaController@destroys');
        Route::post('ishide', 'Referensi\SiswaController@hide');
        Route::post('reorder', 'Referensi\SiswaController@reorder');
        Route::get('get', 'Referensi\SiswaController@ajax');
    });














    // **********************************************************************************************************
    // *                                           BAGIAN BRANDS                                                *
    // **********************************************************************************************************


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



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                              Start Product                          +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('product')->group(function () {
        Route::get('/', 'Referensi\BrandProdukController@index');
        Route::post('update-or-create', 'Referensi\BrandProdukController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandProdukController@destroy');
        Route::post('destroys', 'Referensi\BrandProdukController@destroys');
        Route::post('ishide', 'Referensi\BrandProdukController@hide');
        Route::post('reorder', 'Referensi\BrandProdukController@reorder');
        Route::get('get', 'Referensi\BrandProdukController@ajax');
        Route::post('get-id', 'Referensi\BrandProdukController@ajaxId');
        Route::post('plural', 'Referensi\BrandProdukController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                               End Product                           +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++








    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                            Start Kontak Brand                       +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-kontak')->group(function () {
        Route::get('/', 'Referensi\BrandKontakController@index');
        Route::post('update-or-create', 'Referensi\BrandKontakController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandKontakController@destroy');
        Route::post('destroys', 'Referensi\BrandKontakController@destroys');
        Route::post('ishide', 'Referensi\BrandKontakController@hide');
        Route::post('reorder', 'Referensi\BrandKontakController@reorder');
        Route::get('get', 'Referensi\BrandKontakController@ajax');
        Route::post('plural', 'Referensi\BrandKontakController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                             End Kontak Brand                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++






    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                            Start Folder Brand                       +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-folder')->group(function () {
        Route::get('/', 'Referensi\BrandFolderController@index');
        Route::post('update-or-create', 'Referensi\BrandFolderController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandFolderController@destroy');
        Route::post('destroys', 'Referensi\BrandFolderController@destroys');
        Route::post('ishide', 'Referensi\BrandFolderController@hide');
        Route::post('reorder', 'Referensi\BrandFolderController@reorder');
        Route::get('get', 'Referensi\BrandFolderController@ajax');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                             End Folder Brand                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++




    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                            Start Upload Brand                       +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-upload')->group(function () {
        Route::get('/', 'Referensi\BrandImageController@index');
        Route::post('update-or-create', 'Referensi\BrandImageController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandImageController@destroy');
        Route::post('destroys', 'Referensi\BrandImageController@destroys');
        Route::post('ishide', 'Referensi\BrandImageController@hide');
        Route::post('reorder', 'Referensi\BrandImageController@reorder');
        Route::get('get', 'Referensi\BrandImageController@ajax');
        Route::post('plural', 'Referensi\BrandImageController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                             End Upload Brand                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++




    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                            Start Brand Slider                       +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-slider')->group(function () {
        Route::get('/', 'Referensi\BrandSliderController@index');
        Route::post('update-or-create', 'Referensi\BrandSliderController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandSliderController@destroy');
        Route::post('destroys', 'Referensi\BrandSliderController@destroys');
        Route::post('ishide', 'Referensi\BrandSliderController@hide');
        Route::post('reorder', 'Referensi\BrandSliderController@reorder');
        Route::get('get', 'Referensi\BrandSliderController@ajax');
        Route::post('get-id', 'Referensi\BrandSliderController@ajaxId');
        Route::post('plural', 'Referensi\BrandSliderController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                             End Brand Slider                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                            Start Brand Slider                       +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-setting-beranda')->group(function () {
        Route::get('/', 'Referensi\BrandBlockController@index');
        Route::post('update-or-create', 'Referensi\BrandBlockController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandBlockController@destroy');
        Route::post('destroys', 'Referensi\BrandBlockController@destroys');
        Route::post('ishide', 'Referensi\BrandBlockController@hide');
        Route::post('reorder', 'Referensi\BrandBlockController@reorder');
        Route::get('get', 'Referensi\BrandBlockController@ajax');
        Route::post('plural', 'Referensi\BrandBlockController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                             End Brand Slider                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                            Start Brand Slider                       +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::prefix('brand-setting-layout')->group(function () {
        Route::get('/', 'Referensi\BrandBlockTemplateController@index');
        Route::post('update-or-create', 'Referensi\BrandBlockTemplateController@updateOrCreate');
        Route::post('destroy', 'Referensi\BrandBlockTemplateController@destroy');
        Route::post('destroys', 'Referensi\BrandBlockTemplateController@destroys');
        Route::post('ishide', 'Referensi\BrandBlockTemplateController@hide');
        Route::post('reorder', 'Referensi\BrandBlockTemplateController@reorder');
        Route::post('active', 'Referensi\BrandBlockTemplateController@setIsActive');
        Route::get('get', 'Referensi\BrandBlockTemplateController@ajax');
        Route::post('plural', 'Referensi\BrandBlockTemplateController@upload');
    });
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +                             End Brand Slider                        +
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
});
