<?php
// Laravelのマイグレーションファイルで、todos テーブルを作成するためのコード

// use 使用するファザードを記述している。
// ファザード自動でインスタンス化して簡単にクラスにアクセスできる。
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// このマイグレーションが作成するテーブル
// カラム名	データ型	説明
// id	bigIncrements	自動で増える主キーID
// content	string	ToDoの内容（255文字以内）
// created_at, updated_at	timestamp	自動で日時が記録されるカラム

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schemaファザードでcreateメソッドを使い、
        // 第１引数でテーブル名、
        // 第２引数にクロージャ

        // クロージャ内の第１引数に
        // Blueprintオブジェクトを指定して
        // 第２引数にBlueprintクラスの＄tableインスタンスを引数にしてテーブルを作成
        Schema::create('todos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}