<?php

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

Route::get('/migrate', function () {
    if(Artisan::call('migrate:fresh', ["--force" => true ])){
        return 'HECHO';
    }else{
        return 'ERROR';
    }
});

Route::get('/key-generate', function () {
    return Artisan::call('key:generate');
});

Route::group(['middleware' => [
    'auth:sanctum',
    'verified'
]], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/pages', function () {
        return view('admin.pages');
    })->name('pages');

    Route::get('/posts', function () {
        return view('posts.posts');
    })->name('posts');
});

Route::get('/post/{urlslug}', \App\Http\Livewire\Frontpage::class);
//Route::get('/', \App\Http\Livewire\Frontpage::class);
//