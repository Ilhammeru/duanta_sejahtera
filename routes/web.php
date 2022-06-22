<?php

use App\Http\Controllers\BookingInController;
use App\Http\Controllers\ContainerSizeTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Master\DivisionController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('welcome');

Route::get('/user', function() {
    $pageTitle = "Template User";
    return view('user', compact('pageTitle'));
})->name('template.user');
Route::get('/template/profile', function() {
    $pageTitle = "Template Profile";
    return view('profile', compact('pageTitle'));
})->name('template.profile');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::get('/register', function() {
    return view('register');
})->name('register');

Route::get('/password-reset', function() {
    return 'password reset';
})->name('password.reset');
Route::get('/password-email', function() {
    return 'password email';
})->name('password.email');
Route::get('/password-request', function() {
    return 'password request';
})->name('password.request');

Route::group(['middleware' => ['auth', 'role:admin:superadmin']], function() {
    // begin::region
    Route::get('/region/getCity/{id}', [RegionController::class, 'getCity']);
    Route::get('/region/getDistrict/{id}', [RegionController::class, 'getDistrict']);
    // end::region
});

Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::get('/dashboard', function() {
        $pageTitle = "Dashboard";
        $auth = User::with([
            'userRole' => function($query) {
                $query->select('user_id', 'role_id', 'id');
            },
            'userRole.role' => function($query) {
                $query->select('id', 'name', 'slug');
            }
        ])
            ->find(Auth::id());
        return view('dashboard', compact('pageTitle', 'auth'));
    })->name('dashboard');

    // *************************************** MASTER ********************************* //
    // begin::division
    Route::get('/division', [DivisionController::class, 'index'])->name('division.index');
    Route::get('/division/json', [DivisionController::class, 'json'])->name('division.json');
    Route::post('/division/store', [DivisionController::class, 'store'])->name('division.store');
    Route::put('/division/update/{id}', [DivisionController::class, 'update'])->name('division.update');
    Route::get('/division/detail/{id}', [DivisionController::class, 'detail'])->name('division.detail');
    Route::delete('/division/{id}', [DivisionController::class, 'destroy'])->name("division.delete");
    // end::division

    // begin::container-size
    Route::get('/container-size-type/json', [ContainerSizeTypeController::class, 'json'])->name('container-size-type.json');
    Route::resource('container-size-type', ContainerSizeTypeController::class);
    // end::container-size

    // *************************************** USER MANAGEMENT ********************************* //
    // begin::role
    Route::get('/roles/json', [RoleController::class, 'json'])->name('roles.json');
    Route::resource('roles', RoleController::class);
    // end::role
    // begin::user
    Route::get('/user/json', [UserController::class, 'json'])->name('user.json');
    Route::delete('/users/photo/{id}', [UserController::class, 'deletePhoto'])->name('user.delete.photo');
    Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update');
    // Route::get('user.in', UserController::class);
    Route::get('/users', [UserController::class, 'index'])->name("user.index");
    Route::get('/users/{id}', [UserController::class, 'show'])->name('user.show');
    Route::post('/users/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    // end::user
    // begin::customer
    Route::get('/customers/json', [CustomerController::class, 'json'])->name('customers.json');
    Route::get('/customer/change-service/{id}', [CustomerController::class, 'changeService'])->name('customer.changeService');
    Route::get('/customer/change-contract/{id}', [CustomerController::class, 'changeContract'])->name('customer.changeContract');
    Route::post('/customer/change-service/{id}', [CustomerController::class, 'storeService'])->name('customer.service.store');
    Route::post('/customer/change-contract/{id}', [CustomerController::class, 'storeContract'])->name('customer.contract.store');
    Route::get('/customer/init/{id}/{type}', [CustomerController::class, 'detailInit'])->name('customer.init');
    Route::post('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/delete/{id}', [CustomerController::class, 'deleteContractPhoto'])->name('customers.deleteContractPhoto');
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/getFormService/{count}', [CustomerController::class, 'getFormService'])->name('customers.getFormService');
    Route::get('/customer/edit/form/{type}/{id}', [CustomerController::class, 'showForm'])->name('customers.edit.form');
    // end::customer
    // begin::services
    Route::get('/services/json', [ServiceController::class, 'json'])->name('services.json');
    Route::resource('services', ServiceController::class);
    Route::post('/services/update/{id}', [ServiceController::class, 'update'])->name('services.update');
    // end::services
    // begin::booking-in
    Route::get('/booking-in/json', [BookingInController::class, 'json'])->name('booking-in.json');
    Route::get('/booking-in/detail-container/{id}', [BookingInController::class, 'detailContainer'])->name('booking-in.detailContainer');
    Route::get('/booking-in/print-container/{id}', [BookingInController::class, 'printContainerView'])->name('booking-in.printContainerView');
    Route::resource('booking-in', BookingInController::class);
    // end::booking-in
});