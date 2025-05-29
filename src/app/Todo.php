<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Todo モデルは Eloquent の Model クラスを継承していて、
// データベースと簡単にやりとりできます。
// クラス名→テーブル名
class Todo extends Model
{
    use SoftDeletes;
    protected $table = 'todos';
    // マスアサインメント（Mass Assignment）を許可するカラムを指定。
    // fill() メソッドや create() メソッドを使って、
    // 複数の属性を一括で代入できるようになります。
    // 指定していないと、MassAssignmentException（マスアサインメント例外）
    protected $fillable = [
        'content',
    ];
}


// Eloquentとは？
// Eloquent は Laravel に標準搭載されている ORM で、
// データベースのテーブルと PHP のクラス（モデル）を対応させる仕組みです。
// これにより、SQL を直接書かずに、PHP のコードだけでデータベースの操作ができます。

// モデルって何？
//「DBの1つのテーブルを表すクラス」**で、
// データの取得・保存などをEloquent ORMで扱えるようにするもの！