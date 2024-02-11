<?php

use App\Http\Middleware\DatabaseSwitcher;
use Illuminate\Support\Facades\Hash;
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

Route::get("/reset",function(){
    dd(Hash::make("password"));
});
Route::get("/current-connection",function(){
    dd(DB::connection()->getDatabaseName());
})->middleware([DatabaseSwitcher::class]);

Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
Route::get('/clear-config', function() {
    $exitCode = Artisan::call('config:clear');
    return '<h1>Clear Config cleared</h1>';
});
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});
Route::get('/clear-view', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>Cache facade value cleared</h1>';
});
Route::get('/clear-route', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Cache facade value cleared</h1>';
});
Route::get('/', function () {
    if(auth()->check()){
        return redirect()->route("admin.dashboard");
    }
    return view('login');
})->name('login');

include "admin.php";

Route::post('/login', 'App\Http\Controllers\AuthController@login')->name('auth.login');

Route::get('logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    session()->invalidate();
    session()->regenerate(true);
    return redirect()->route('login');
})->name('logout');

Route::get('forgot/password', function (){
    return view('forgot');
})->name('forgot.password');

Route::post('/check/email', 'App\Http\Controllers\AuthController@checkEmail')->name('auth.check.email');
Route::get('reset/password/{email}', 'App\Http\Controllers\AuthController@restPasswordView')->name('reset.password.view');
Route::post('password/change/{id}', 'App\Http\Controllers\AuthController@passwordChange')->name('reset.password.change');


//Route::get('/user-profile', function () {
//    return view('admin.users.view_profile');
//})->name('user.profile');
//Route::get('/edit-user', function () {
//    return view('admin.users.add_edit');
//})->name('add.edit.users');

//Route::get('vendor/manage', 'App\Http\Controllers\AuthController@vendorManage');
