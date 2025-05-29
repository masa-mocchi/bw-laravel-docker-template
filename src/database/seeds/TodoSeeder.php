<?php

use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // todos テーブルの全データを削除します。
        // さらに、AUTO_INCREMENT（IDのカウント）もリセットされます。
        DB::table('todos')->truncate(); // 追記
        
        $testData = [
            [
                // $testData は、挿入するダミーデータの配列です。
                // content はToDoの内容。
                // created_at / updated_at は
                // 現在の日時（Laravelの now() ヘルパー関数）を使っています。
                'content' => 'PHP Appセクションを終える',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Laravel Lessonを終える',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // 上で用意したデータを todos テーブルに挿入しています。
        DB::table('todos')->insert($testData);
    }
}

// 実行コマンド
// php artisan db:seed --class=TodoSeeder
