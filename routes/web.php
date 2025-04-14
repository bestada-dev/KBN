<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\LoginCheck;
use App\Http\Middleware\CheckMediaAccess;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use App\Form;

Route::get('private-files/{folder}/{fileName}', function ($folder,$fileName) {
    $filePath = storage_path('app/private/'. $folder . '/' . $fileName);
    return response()->file($filePath);
})->middleware(CheckMediaAccess::class);

// LOGOUT & CHECK LOGIN
Route::GET('/login', 'AuthController@page')->name('login');
Route::GET('checkLogin/{id}', 'AuthController@checkLogin');
Route::GET('user/verification/{id}', 'AuthController@verification');
Route::GET('reset-password/{param}', 'AuthController@handleResetPassword');
// Route::GET('forgot-password', 'PagesController@forgotPassword');
Route::GET('logout', 'AuthController@logout')->name('logout');


Route::prefix('forgot-password')->group(function () {
    Route::GET('/', 'ForgotPasswordController@index')->name('pages.register.form.index');
    Route::GET('/form-otp/{id}', 'ForgotPasswordController@formOtp')->name('pages.register.form.index');
    Route::GET('/update/{id}', 'ForgotPasswordController@formForgotPassword')->name('pages.register.form.index');
});



// Middleware buat cek login
Route::middleware([LoginCheck::class])->group(function(){

    Route::prefix('superadmin')->group(function () {

        Route::GET('/landing-page', function () {
            return view('pages.admin.halaman-arah.index');
        })->name('halaman-arah');

        Route::prefix('panduan')->group(function () {
            Route::GET('/', 'Superadmin\PanduanController@index')->name('pages.admin.panduan.index');
        });
        Route::prefix('admin')->group(function () {
            Route::GET('/', 'Superadmin\AdminController@index')->name('pages.admin.form.index');
            Route::GET('/post', 'Superadmin\AdminController@post')->name('pages.admin.setup.form.post');
            Route::GET('/update/{id}', 'Superadmin\AdminController@update')->name('pages.admin.setup.form.update');
        });

        Route::prefix('property')->group(function () {
            Route::GET('/', 'Superadmin\PropertyController@index')->name('pages.property.form.index');
            Route::GET('/create', 'Superadmin\PropertyController@create')->name('pages.property.setup.form.create');
            Route::GET('/update/{id}', 'Superadmin\PropertyController@update')->name('pages.property.setup.form.update');
        });

        Route::prefix('log-activity')->group(function () {
            Route::GET('/', 'Superadmin\LogActivityController@index')->name('pages.log.activity.form.index');
        });

        Route::prefix('message')->group(function () {
            Route::GET('/', 'Superadmin\MessageController@index')->name('pages.message.form.index');
        });

        Route::prefix('master')->group(function () {
            Route::prefix('zonning')->group(function () {
                Route::GET('/', 'Superadmin\ZoningController@index')->name('pages.zoning.form.index');
                Route::GET('/post', 'Superadmin\ZoningController@post')->name('pages.zoning.setup.form.post');
                Route::GET('/edit/{id}', 'Superadmin\ZoningController@edit')->name('pages.zoning.setup.form.edit');
            });

            Route::prefix('category')->group(function () {
                Route::GET('/', 'Superadmin\CategoryController@index')->name('pages.category.form.index');
                Route::GET('/post', 'Superadmin\CategoryController@post')->name('pages.category.setup.form.post');
                Route::GET('/edit/{id}', 'Superadmin\CategoryController@edit')->name('pages.category.setup.form.edit');
            });
        });

        Route::GET('/dashboard', 'Superadmin\DashboardController@index')->name('pages.admin.dashboard.index');
    });

});


// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~`~~~~ Landing Page Routes ~~~~~~`~~~~~~~~~~~~~~~~~~
Route::get('/', 'LandingPageController@index')->name('home');
Route::post('/message', 'LandingPageController@post_message')->name('message');
Route::get('/notification', 'NotificationController@getNotifications')->name('notifications.get');
Route::get('/about-us', 'LandingPageController@aboutUs')->name('about-us');
Route::get('/contact-us', 'LandingPageController@contactUs')->name('contact-us');
Route::get('/product/{id}', 'LandingPageController@productDetail')->name('product.show');
Route::get('/product-pelatihan-public', 'LandingPageController@productPelatihanPublic')->name('product-pelatihan-public');
Route::get('/product-pengembangan-diri', 'LandingPageController@productPengembanganDiri')->name('product-pengembangan-diri');
Route::get('/search-result', 'LandingPageController@search')->name('product.search');
Route::prefix('landing-page')->group(function () {
    Route::GET('/create', 'GenerateDynamicFormController@create')->name('pages.dynamic.form.create');
    Route::GET('/edit/{id?}', 'GenerateDynamicFormController@edit')->name('pages.dynamic.form.edit');
});
// @ms
Route::get('/change-language', 'ChangeLangController@changeLanguage')->name('change_language');
// endms

// Mendapatkan daftar notifikasi
Route::get('/notifications', 'NotificationController@getNotifications')->name('notifications.get');
// Menandai notifikasi sebagai dibaca
Route::post('/notifications/mark-as-read', 'NotificationController@markAsRead')->name('notifications.markAsRead');


// ===============Running crobjob update status===============
require __DIR__ . '/data/cronjob.php';

// Include file rute lainnya
require __DIR__ . '/data/insert_data_dummy.php';



// Route::get('view-a', function () {
//     return view('components.a');
// });

// Route::get('view-b', function () {
//     return view('components.b');
// });

// Route::get('view-c', function () {
//     return view('components.c');
// });
