<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;


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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//一覧表示用
Route::get('/productlist', 'ProductController@showList')->name('productlist');
Route::get('/productlist', 'ProductController@searchList')->name('searchlist');
//商品新規登録
Route::get('/regist', 'CompanyController@showRegist')->name('regist');
Route::post('/regist','ProductController@registSubmit')->name('submit');
//商品情報詳細
Route::get('/detail/{id}', 'ProductController@showDetail')->name('detail');
//商品情報編集
Route::get('/edit/{id}', 'ProductController@edit')->name('edit');
Route::put('/edit/update/{id}', 'ProductController@update')->name('update');
//削除処理
Route::post('/destroy{id}', 'ProductController@destroy')->name('destroy');