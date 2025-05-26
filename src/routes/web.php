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

// Route::get()：Laravelのルーティング定義関数
// GET /（トップページ）にアクセスが来たとき、
// 無名関数（クロージャ）が呼ばれ、welcomeビューが表示される。
// 引数
// '/’（string）：URLパス
// function () { return view('welcome'); }：クロージャ（関数）
// クロージャの戻り値
// view('welcome')は、Illuminate\View\View型（HTMLとして返されるビューのインスタンス）
// Route::get() はstaticメソッド

Route::get('/', function () {
    return view('welcome');
});
// 文字列でコントローラーメソッドを指定する**「コントローラールート」**
// GET /todo にアクセスすると、
// App\Http\Controllers\TodoController クラスの index メソッドが実行される。
// '/todo'：URLパス（string）
// 'TodoController@index'：コントローラーとメソッドを示す文字列
// 関数の種類
// Laravelのメソッドチェーン式API。Route::get() はstaticメソッド

// GET /todo/createにアクセスしたとき、フォーム表示用の create() を呼ぶ。
// .name('todo.create') でルート名を設定（リンク生成に便利）

// POSTリクエスト
// フォームからの送信を処理する
// store()メソッドでDBに新規データ保存する想定


// Route::get('/todo', 'TodoController@index');
Route::get('/todo', 'TodoController@index')->name('todo.index');
Route::get('/todo/create', 'TodoController@create')->name('todo.create');
Route::post('/todo', 'TodoController@store')->name('todo.store');
Route::get('/todo/{id}', 'TodoController@show')->name('todo.show');
Route::get('/todo/{id}/edit', 'TodoController@edit')->name('todo.edit');
Route::put('/todo/{id}', 'TodoController@update')->name('todo.update');

// 「/todo というURLにGETリクエストが来たら、
// TodoControllerのindexメソッドを実行する」
// use App\Http\Controllers\TodoController;
// Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');

// GET /todo にアクセスされたら…
// TodoController の index() メソッドを実行！
// 名前は todo.index とつける（リンク作成時に便利）

// メソッド	説明
// get()	ページを表示する（一覧・詳細など）
// post()	データを送信する（保存など）
// put() / patch()	データを更新する
// delete()	データを削除する