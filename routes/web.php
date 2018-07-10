<?php

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

//显示创建帖子页面
Route::get('/threads/create', 'ThreadController@create')->name('thread.create');
//帖子详细
Route::get('/threads/{channel}/{thread}','ThreadController@show')->name('thread.show');
//保存帖子的回复
Route::post('/threads/{channel}/{thread}/replies','ReplyController@store')->name('reply.store');
//创建帖子
Route::post('/threads','ThreadController@store')->name('thread.store');
//帖子列表
Route::get('/threads', 'ThreadController@index')->name('thread.index');
//显示编辑帖子页面
Route::get('/threads/{thread}/edit', 'ThreadController@edit')->name('thread.edit');
//保存更新帖子
Route::patch('/threads/{thread}', 'ThreadController@update')->name('thread.update');
//删除帖子
Route::delete('/threads/{thread}', 'ThreadController@destroy')->name('thread.destroy');