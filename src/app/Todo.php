<?php
// このモデルの名前空間を定義しています。
// Laravel の標準構成では、
// モデルは app/ ディレクトリに置かれ、App 名前空間を使います。
namespace App;

// Laravel の Eloquent モデル機能を使うために、Model クラスを読み込む。
// Todo クラスはこの Model を継承しています。
// これを継承することで、
// Eloquent の機能（検索・挿入・更新など）を使えるようになります。
use Illuminate\Database\Eloquent\Model;

// Todo モデルは Eloquent の Model クラスを継承していて、
// データベースと簡単にやりとりできます。
// クラス名→テーブル名
class Todo extends Model
{
    // 使用するテーブル名を明示的に指定
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