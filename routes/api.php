<?php

use App\Http\Controllers\Superadmin\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenMiddleware;


// AUTH
Route::post('login', 'AuthController@login');
Route::post('reset-password', 'AuthController@requestPasswordReset');
Route::post('change-password/{type?}', 'AuthController@updatePassword');

// SEND EMAIL
Route::prefix('email')->group(function(){
	Route::get('verify', 'EmailController@verify');
});

Route::prefix('register')->group(function(){
    Route::post('/validasi_otp', 'RegisterController@verifyOTP');
    Route::post('/cek-email', 'RegisterController@cekEmail');
    Route::post('/post-register-admin/{id}', 'RegisterController@registerAdmin');
    Route::post('/post-register-vendor/{id}', 'RegisterController@registerVendor');
    Route::post('/post-register-perusahaan/{id}', 'RegisterController@registerPerusahaan');
    Route::post('/post-register-employe/{id}', 'RegisterController@registerEmploye');

});

Route::prefix('forgot-password')->group(function(){
    Route::post('/validasi_otp', 'ForgotPasswordController@verifyOTP');
    Route::post('/cek-email', 'ForgotPasswordController@cekEmail');
    Route::post('/change-password/{id}', 'ForgotPasswordController@changePassword');

});

Route::prefix('contacts')->group(function(){
	Route::post('data-table', 'Admin\ContactController@dataTable');
	Route::post('create', 'Admin\ContactController@store');
	Route::post('delete', 'Admin\ContactController@delete');
});

Route::get("/product-images/{productId}", 'LandingPageController@getProductImages');

Route::middleware([TokenMiddleware::class])->group(function(){


	// USERS
	Route::prefix('superadmin')->group(function(){

        Route::prefix('admin')->group(function(){
            Route::post('data-table', 'Superadmin\AdminController@dataTable')->name('admin.list');
            Route::post('create', 'Superadmin\AdminController@create');
            Route::post('update/{id}', 'Superadmin\AdminController@update');
            Route::post('changeStatus/{id}', 'Superadmin\AdminController@changeStatus');
            Route::post('delete', 'Superadmin\AdminController@delete');
        });

        Route::prefix('property')->group(function(){
            Route::post('data-table', 'Superadmin\PropertyController@dataTable')->name('property.list');
            Route::post('store', 'Superadmin\PropertyController@store');
            Route::post('edit/{id}', 'Superadmin\PropertyController@edit');
            Route::post('changeStatus/{id}', 'Superadmin\PropertyController@changeStatus');
            Route::post('delete', 'Superadmin\PropertyController@delete');
            Route::post('delete-image', 'Superadmin\PropertyController@deleteImage');
        });

        Route::prefix('log-activity')->group(function(){
            Route::post('data-table', 'Superadmin\LogActivityController@dataTable')->name('log-activity.list');
        });

        Route::prefix('message')->group(function(){
            Route::post('data-table', 'Superadmin\MessageController@dataTable')->name('message.list');
        });

        Route::prefix('master')->group(function(){
            Route::prefix('zoning')->group(function(){
                Route::post('data-table', 'Superadmin\ZoningController@dataTable')->name('zoning.list');
                Route::post('create', 'Superadmin\ZoningController@create');
                Route::post('update/{id}', 'Superadmin\ZoningController@update');
                Route::post('delete', 'Superadmin\ZoningController@delete');
            });

            Route::prefix('category')->group(function(){
                Route::post('data-table', 'Superadmin\CategoryController@dataTable')->name('zoning.list');
                Route::post('create', 'Superadmin\CategoryController@create');
                Route::post('update/{id}', 'Superadmin\CategoryController@update');
                Route::post('delete', 'Superadmin\CategoryController@delete');
            });
        });

        Route::prefix('dashboard')->group(function () {
            Route::post('monthly-visitors', [DashboardController::class, 'getMonthlyVisitorsDataAPI']);
            Route::post('monthly-visitors-by-country', [DashboardController::class, 'getMonthlyVisitorsByCountryDataAPI']);
            Route::post('master-property-categories', [DashboardController::class, 'masterCategoryPropertiesDataAPI']);
            Route::post('ten-most-viewed-properties', [DashboardController::class, 'tenMostViewedPropertiesDataAPI']);
        });
        
    });


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~`~~~~ API BUAT Landing Page ~~~~~~`~~~~~~~~~~~~~~~~~~

	Route::prefix('landing-page')->group(function(){
		Route::post('/home', 'LandingPageController@saveHome');
	});



});


Route::get('a', 'LandingPageController@a');
Route::delete('a/{id}', 'LandingPageController@adelete');
