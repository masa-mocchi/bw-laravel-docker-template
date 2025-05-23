<?php

// この DatabaseSeeder クラスは、
// Laravel のシーディング機能を使ってデータベースに初期データを投入するためのクラス
use Illuminate\Database\Seeder;

// シーダークラスを継承して使う宣言
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TodoSeeder::class,
        ]);

        // $this->call(UsersTableSeeder::class);
    }
}
