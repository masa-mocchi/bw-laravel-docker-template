# Laravel Lesson レビュー①

## Todo一覧機能

### Todoモデルのallメソッドで実行しているSQLは何か
SELECT * FROM todos;
（todosテーブルから全件取得するSQL）

### Todoモデルのallメソッドの返り値は何か
Illuminate\Database\Eloquent\Collection クラスのインスタンスで、Todoモデルのコレクション。

Collectionクラスのインスタンス。
CollectionクラスにはTodoインスタンスが格納されている。
Todosテーブルのカラム、レコードを配列っぽい形で取得
データ存在時の返り値はarrayで返される。

### 配列の代わりにCollectionクラスを使用するメリットは
Collectionインスタンスが配列操作に特化したクラスで、
Laravelを利用する場合にはより効率的に操作できるため。

Laravelの便利なメソッドが多数使える。
チェーンメソッド（filter, map, sortなど）が使える。
読みやすく、保守性が高いコードになる。

### view関数の第1・第2引数の指定と何をしているか
第1引数：ビューのパス（例: 'todos.index'）
第2引数：ビューに渡すデータ（例: ['todos' => $todos]）
→ 指定したビューを表示し、データをビューに渡す

第１引数には表示する画面のview名を指定、第２引数で連想配列でviewにデータを渡している。
第２引数の連想配列は、bladeでの変数名->代入したい値という内容になっている。

### index.blade.phpの$todos・$todoに代入されているものは何か
$todos: コントローラから渡されたTodoモデルのコレクション
$todo: foreachなどで1件ずつ取り出したTodoモデルのインスタンス

view関数で第２引数で指定されたデータがview内で使用できる変数に代入されている。
＄todosが全てのtodoデータ、$todoはループ中に$todosから取り出された１つのtodoデータとなる。

## Todo作成機能


### Requestクラスのallメソッドは何をしているか
フォームから送信された全入力データを配列で取得する。
HTTPリクエストから送信された全てのデータを連想配列として取得している。

### fillメソッドは何をしているか
配列で渡された値を、モデルの属性に一括で代入する
（ただし$fillableで許可されたカラムのみ）

Todoインスタンスの各プロパティに一括で代入する
Modelのfill()メソッドを使用することで引数に指定した連想配列を一括代入できる。

### $fillableは何のために設定しているか
fillやcreateなどで一括代入を許可するカラムを制限し、
マスアサインメント（不正な代入）を防ぐため。

第三者からの意図しないカラムの追加を防いでいる。

### saveメソッドで実行しているSQLは何か
新規作成時：INSERT
INSERT INTO todos (title, ...) VALUES (...);
更新時：UPDATE
UPDATE todos SET title = ... WHERE id = ...;

### redirect()->route()は何をしているか
第１引数にrouteを定義した名前を記述する。
指定したルート名にリダイレクト（HTTPのLocationヘッダを返す）する。

ルートが変更された場合にも名前で一括管理できるため保守性が高い。

## その他

### テーブル構成をマイグレーションファイルで管理するメリット
DB構成をコードで管理できる（バージョン管理も可能）
→SQLを知らなくてもPHPコードでテーブルを操作できる。
チーム開発で統一されたDB環境を再現できる
→マイグレーションファイルをGitで共有することで開発者全員が同じテーブルを作成できる
現在のデータベースの状態を他の開発者に共有することができる。
デプロイ時に簡単にテーブルを作成できる
デプロイ→開発していたWebアプリをサーバーやクラウドにアップロードして
使えるように公開・配置すること。

### マイグレーションファイルのup()、down()は何のコマンドを実行した時に呼び出されるのか
up() → php artisan migrate で呼ばれる
これは、データベースに新しいテーブル、カラム、インデックスを追加するために使用する。

down() → php artisan migrate:rollback で呼ばれる
これは、up()で実行された変更を取り消すために使用する。

### Seederクラスの役割は何か
開発・テスト用の初期データをDBに挿入する処理を記述する
開発者間で差異が発生しないようするための役割がある。

### route関数の引数・返り値・使用するメリット
web.php内
Route::get(/todo','TodoController@index')で
第１引数：ルート名（文字列）、
第２引数：Controllerとそのメソッドを指定することによってそのルートのURLを作れる。
また、->name('todo.index')を追記することで
上記のルートに対応するURLに名前を定義することができる

パラメータ（配列）
返り値：対応するURL文字列
メリット：URL変更時にコード全体を修正せずに済む（ルート名で管理）ため、
保守性と可読性が高い。


### @extends・@section・@yieldの関係性とbladeを分割するメリット
親テンプレート（継承元）となるbase.blade.php部分に@yield（’文字列’）を記述して内容を埋め込み、
子テンプレート（継承先）に@extends('\resources\views以降のパス')を使うことで継承する。

子テンプレート内で@section（’文字列’）を使うことで親の@yield（’文字列’）に対応した内容を
埋め込むことができる。

bladeを分割することで、複数のbledeを組み合わせて使うことができ、
重複するコードを共通化することで再利用性・保守性・見通しを向上できる。

@extends：レイアウトファイルを継承
@section：継承元の特定部分にコンテンツを挿入
@yield：継承元で挿入位置を定義

### @csrfは何のための記述か
クロスサイトリクエストフォージェリ（CSRF）対策用のトークンをフォームに埋め込む
→ LaravelがPOSTリクエストの正当性を検証し、悪意あるリクエストを防ぐことができる。

自動的にCSRFトークンを作成
CSRFトークンを一意のキーとしてHTMLフォームを埋め込み、サーバー側でトークンの検証を行う。

### {{ }}とは何の省略系か
<?php echo e(); ?> の省略系
→ 変数をHTMLエスケープして安全に表示する
特殊文字を文字列として登録し、XSS（クロスサイトスクリプティング）攻撃を防ぐ。
&→&amp;
<→&lt;
>→&gt;
"→&quot;
'→&#039;,&apos;



<!-- バックスラッシュはOption+￥で打てる -->

### Laravel１口頭レビューの課題
# １．Modelクラスのインスタンスのデータ型

A.「object（オブジェクト）」型

<!-- $todo = new Todo();
Eloquentモデルクラスのインスタンス
App\Models\Todo
Todoモデル


    public function index()
    {
        $todo = new Todo();
        dd($todo);
        App\Todo {#230 ▼
            #table: "todos"
            #fillable: array:1 [▶]
            #connection: null
            #primaryKey: "id"
            #keyType: "int"
            +incrementing: true
            #with: []
            #withCount: []
            #perPage: 15
            +exists: false
            +wasRecentlyCreated: false
            #attributes: []
            #original: []
            #changes: []
            #casts: []
            #dates: []
            #dateFormat: null
            #appends: []
            #dispatchesEvents: []
            #observables: []
            #relations: []
            #touches: []
            +timestamps: true
            #hidden: []
            #visible: []
            #guarded: array:1 [▶]
          }
        $todos = $todo->all();
        return view('todo.index', ['todos' => $todos]);
    }

    public function index()
    {
        $todo = new Todo();
        // dd($todo);
        $todos = $todo->all();
        // dd($todos);
        return view('todo.index', ['todos' => $todos]);
    }
Illuminate\Database\Eloquent\Collection {#274 ▼
  #items: array:12 [▼
    0 => App\Todo {#275 ▼
      #table: "todos"
      #fillable: array:1 [▶]
      #connection: "mysql"
      #primaryKey: "id"
      #keyType: "int"
      +incrementing: true
      #with: []
      #withCount: []
      #perPage: 15
      +exists: true
      +wasRecentlyCreated: false
      #attributes: array:4 [▶]
      #original: array:4 [▶]
      #changes: []
      #casts: []
      #dates: []
      #dateFormat: null
      #appends: []
      #dispatchesEvents: []
      #observables: []
      #relations: []
      #touches: []
      +timestamps: true
      #hidden: []
      #visible: []
      #guarded: array:1 [▶]
    }
    1 => App\Todo {#276 ▶}
    2 => App\Todo {#277 ▶}
    3 => App\Todo {#278 ▶}
    4 => App\Todo {#279 ▶}
    5 => App\Todo {#280 ▶}
    6 => App\Todo {#281 ▶}
    7 => App\Todo {#282 ▶}
    8 => App\Todo {#283 ▶}
    9 => App\Todo {#284 ▶}
    10 => App\Todo {#285 ▶}
    11 => App\Todo {#286 ▶}
  ]
} -->

# ２．TodoControllerの$todosのデータ型,$todoのデータ型

A.$todosのデータ型は、コレクションクラスのオブジェクト型
  $todoはクラスのインスタンスなのでオブジェクト型

<!-- $todo = new Todo(); のデータ型
php
コードをコピーする
$todo = new Todo();
ここで $todo は、App\Models\Todo クラスの**インスタンス（オブジェクト）**です。

データ型：Todo クラスのオブジェクト型

php
コードをコピーする
// 確認方法
var_dump($todo);
// 出力例
object(App\Models\Todo)#123 (0) { ... }

$todos = $todo->all(); のデータ型
php
コードをコピーする
$todos = $todo->all();
all() は Eloquentモデルのメソッドで、データベースから全件取得して、**コレクション（Collection）**として返します。

データ型：Illuminate\Database\Eloquent\Collection

これは、**複数のTodoインスタンスが入った「オブジェクトの配列っぽいもの」**です。Laravelではこの「コレクション型」がよく使われます。

php
コードをコピーする
// 確認方法
dd($todos);
// 出力例（ダンプで見れる）
Collection {#456 ▼
  #items: array:3 [▼
    0 => Todo {#789 ...}
    1 => Todo {#790 ...}
    2 => Todo {#791 ...}
  ]
} -->
<!-- 変数名	型	中身のイメージ
$todo	App\Models\Todo（オブジェクト型）	1つのTodoモデル（空っぽ）
$todos	Illuminate\Database\Eloquent\Collection -->

# ３．コレクションクラスのインスタンスと普通のインスタンスの違い

A.
「普通のインスタンス」は、
クラスから生成された1つのオブジェクトのことです。
<!-- $todo = new Todo();
$todo は App\Models\Todo というクラスのインスタンスです。
→ これは1件分のデータを扱う単体オブジェクトです。 -->

Collection クラスのインスタンスは、
複数のモデルのインスタンス（つまりオブジェクト）をまとめた「箱」です。
各レコードを取り出せる
配列だとできない
<!-- Eloquentでデータを複数件取得すると、Laravelはこの「コレクション」として返してくれます。 -->

<!-- 比較項目	普通のインスタンス ($todo)	コレクションクラスのインスタンス ($todos)
データの数	1件だけ	複数件（0件もありえる）
型	App\Models\Todo	Illuminate\Database\Eloquent\Collection
主な使い道	1レコードの作成、更新、削除など	一覧表示、ループ処理、フィルタなど
便利なメソッド	save(), delete() など	map(), filter(), pluck() など -->

# ４．viewはメソッド？関数？

A.
グローバル関数
<!--
Laravelの view() は、PHPのグローバル関数として定義されています。
だから、クラスやインスタンスを使わずに、直接呼び出せるんです。

view() は、Laravelに最初から用意されている ヘルパ関数（Helper Function）で、
Bladeテンプレートなどのビューを返す役割があります。
「メソッド」との違いは？
項目	関数（view()）	メソッド（例：$request->input()）
所属	クラスに属さない	クラスに属する
呼び出し方	view()	$オブジェクト->メソッド名()
例	view('todo.index')	$todo->save() -->

## view() の中身
<!-- Laravelの view() 関数は、実は裏側で ViewFactory というクラスのメソッドを呼び出しています。

function view($view = null, $data = [], $mergeData = [])
{
    return app('view')->make($view, $data, $mergeData);
}
つまり：

「関数」として簡単に使えるけど
内部では「クラスのメソッド（make）」を呼んでいる
という、Laravelらしい便利で賢い仕組みです -->

# 5．$indexメソッドのall()メソッドのステートメントはなんですか。

A.
Eloquentのall()メソッドで、SQL的には SELECT * FROM todos を実行している

# 6.storeメソッドの引数(Request $Request)を調べる

A.
Requestクラス を使い、formタグからのPOSTデータを受け取る。
$requestに格納。内容はtokenとcontentの連想配列２つ。
$inputsに入る。
$todoにcontentだけ入る。fillable()でそう設定しているから。
insertする。

$todo = new Todo();
$todo->fill($inputs);
$todo->save();


->fill()は$todo->{連想配列のkey} = {連想配列のvalue}を配列の全ての要素に対して行ってくれます。


1. php
public function store(Request $request)

Request は Laravel の Illuminate\Http\Request クラス。
これを使うことで、フォーム送信されたデータ（POSTデータ）を受け取れる。
$requestに新規投稿ページのformタグからのデータが
格納されているので、以下のようにそれぞれ代入する

2. php
$request->all()
$inputs = $request->all();

フォームで送られてきたすべての入力データを配列として取得。
        // dd($request->all());
        // array:2 [▼
        // "_token" => "0PX8lvqTqEbpXD13E9foxdMAccVgmDpnK08iGA0a"
        // "content" => "dd($request->all());"
        // ]
        $inputs = $request->all();
        // dd($inputs);
        // array:2 [▼
        // "_token" => "0PX8lvqTqEbpXD13E9foxdMAccVgmDpnK08iGA0a"
        // "content" => "dd($inputs);"
        // ]

3. $todo = new Todo();
Todoモデルの新しいインスタンス（＝空のToDo）を作成します。

php
$todo = new Todo();
これはまだ中身が空のオブジェクトです。

4. $todo->fill($inputs);
fill()はEloquentモデルのメソッドで、配列のkeyとモデルのプロパティ名が一致すれば、自動で代入します。

php
$inputs = [
    'content' => '牛乳を買う'
];
$todo->fill($inputs); // → $todo->content = '牛乳を買う';
⚠️ ただし注意点！

Todoモデルで「どのプロパティを一括代入していいか」を許可しておかないと動きません。
モデルにこのような記述が必要：
php
protected $fillable = ['content'];




POST はbodyに含まれて＄＿POSTで取り出せている
GETはURLに含まれる