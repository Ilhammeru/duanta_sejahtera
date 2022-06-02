<?php

use App\Http\Controllers\Master\DivisionController;
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
    return view('welcome');
});

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

Route::group(['middleware' => 'auth'], function() {
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
    // end::division
});

