<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// ----- MODELS -------
use App\Models\Page;
use App\Models\Tag;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', function (Request $request){
    $posts = Page::all();
    return response()->json($posts);
});

Route::get('/categories', function (Request $request){
    $categories = Category::all();
    return response()->json($categories);
});

Route::get('/tags', function (Request $request){
    $tags = Tag::all();
    return response()->json($tags);
});

Route::get('/posts/{tag}/category/{category}', function (Request $request){
    $posts = Page::all();
    return response()->json($posts);
});