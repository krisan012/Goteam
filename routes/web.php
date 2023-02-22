<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/{any}', function () {
//     return view('layouts.app');
// })->where('any', '.*');

Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/likes/{pokemon_id}', [LikeController::class, 'store']);
Route::delete('/likes/{like}', [LikeController::class, 'destroy']);

Route::get('/user/likes', [LikeController::class, 'UserLikes']);

Route::get('/pokemon/{pokemon_id}/likes/count', [LikeController::class, 'PokemonLikesCount']);

Route::get('/pokemon/find/like/{pokemon_id}', [LikeController::class, 'find']); // check if the user already like the pokemon
Route::get('/pokemon/{pokemon_id}/likes', [LikeController::class, 'index']);


Route::get('/{vue_capture?}', function() {
    return view('layouts.app');
})->where('vue_capture', '[\/\w\.-]*');


