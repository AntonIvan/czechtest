<?php

use App\Handler\HandlerDB;
use App\Handler\HandlerPageNews;
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
    resolve(HandlerPageNews::class)->getPageNews();
    return view('welcome',['data' => resolve(HandlerDB::class)->readAll()]);
});
Route::get('/{id}', function ($id) {
    return view('new',['data' => resolve(HandlerDB::class)->readOne($id)]);
});
