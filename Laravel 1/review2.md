# Laravel Lesson レビュー②

## Todo編集機能

### @method('PUT')を記述した行に何が出力されているか

A.<input type="hidden" name="_method" value="PUT">

<!--
1. @method('PUT') を記述した行に何が出力されているか
これはBladeテンプレートでフォームのメソッドを疑似的にPUTにするための記述です。HTMLフォームはGETとPOSTしか使えないため、Laravelでは以下のようなhidden inputが出力されます：
<input type="hidden" name="_method" value="PUT">

HTMLのフォームは基本的にGETとPOSTしか使えない。
Laravelでは@method('PUT')を使用することで、
フォーム内にhidden input隠しフィールドを自動で出力している。
これがサーバーに送信されると、Laravelの内部でリクエストメソッドがPUT更新として処理される。
-->
### findメソッドの引数に指定しているIDは何のIDか

A.todosテーブルにあるレコードの主キー（idカラム）

<!--
2. findメソッドの引数に指定しているIDは何のIDか
find($id)の引数には、**データベース上のレコードの主キー（通常はidカラム）**が渡されます。Todoモデルであれば、todosテーブルのidです。

例えば、URLが /todos/5/edit の場合、Laravelのルーティングでコントローラーに 5 が渡され、それを使ってそのTodoの編集フォームを表示する処理になります。
-->

### findメソッドで実行しているSQLは何か
A.select * from `todos` where `id` = idの数値 limit 1;
<!--
例えばidが５であるTodo::find(5)であれば、以下のSQLが実行されます：
select * from `todos` where `id` = 5 limit 1;
findは主キーでレコードを1件だけ取得するメソッドです。IDが存在しない場合は null が返ります。
-->

### findメソッドで取得できる値は何か
A.主キー（id）で検索し、該当する「1件」のEloquentモデルのインスタンス

<!-- 4. findメソッドで取得できる値は何か
指定されたIDに対応するEloquentモデルのインスタンスが返ってきます。該当IDのデータが存在しない場合はnullになります。
主キー（通常はid）で検索し、該当する1件の「Eloquentモデルのインスタンス」を返す
（もし該当データがなければ null を返す） -->

<!-- もし find() に複数IDを渡したら？
Todo::find([1, 3, 5]);
➡️ 複数の主キーに一致するレコードを Collection （モデルの配列っぽいもの）として返す。 -->

### saveメソッドは何を基準にINSERTとUPDATEを切り替えているのか

A.LaravelのEloquentモデルに含まれている$existsというプロパティが、DBに存在するレコードか、否かを基準に切り替えている。
protected $exists = ;
exists = false → 新規レコードとしてINSERT
exists = true → 既存レコードとしてUPDATE
<!--
5. saveメソッドは何を基準にINSERTとUPDATEを切り替えているのか
$todo = new Todo;
$todo->title = '買い物';
$todo->save();
このように new でインスタンス化したときは、$exists は初期状態の false のままです。
なので、save()メソッド内では 新規INSERT処理（performInsert()） が実行されるというわけです。

たとえばfindで取得したオブジェクトに変更を加えてsaveすれば、自動的にUPDATEされます。 -->

## Todo論理削除

### traitとclassの違いとは
A.
classは継承extendsやインスタンス化newができる。しかし、１つのクラスしか継承できない。親のメソッドをそのまま使うことができ、メソッドの上書き（オーバーライド）もできる。コンストラクタ__construct() も受け継げるが、上書き可能。

traitはextendsやnewできないが、共通のメソッドを色んなクラスで使い回すことができる。classにuseすることで組み込む。

<!-- クラス内で同名メソッドを定義すれば、traitで記載済の同名メソッドではなくclassのメソッドが優先される。
trait同士の同名メソッドはエラーとなるため、自分で処理が必要。
解決方法instedof ,  エイリアスで使い分ける -->

<!-- 論理削除で使用したもの
use Illuminate\Database\Eloquent\SoftDeletes;
class Todo extends Model
{
    use SoftDeletes; // 追記

    protected $table = 'todos';

    protected $fillable = [
        'content'
    ];
}
-->

<!--
6. traitとclassの違いとは

traitはクラスにメソッドやプロパティをミックスイン（混ぜる）できる仕組み。
classはオブジェクトを生成するための設計図。
traitは多重継承の代替として利用されます。

trait
役割     「機能の部品」（再利用可能なコード）
使い方   	クラスの中で use して組み込む
継承     できない（インスタンス化も不可）
主な用途	複数クラス間で共通する機能の共有

class
役割「設計図」（インスタンスを作るためのテンプレ）
使い方	new してオブジェクトとして使う
継承    （extends）やインスタンス化できる
主な用途	複数クラス間で共通する機能の共有
-->

<!--
class Car {
    public function drive() {
        echo "ブーン！";
    }
}

$myCar = new Car();
$myCar->drive();  // => ブーン！
クラスは オブジェクトを生成するための「型」です。

Carクラスを使って $myCar という インスタンス（実体） を作っています。

trait（トレイト）とは？
php
コードをコピーする
trait CanFly {
    public function fly() {
        echo "パタパタ飛んだ！";
    }
}

class Bird {
    use CanFly;
}

$bird = new Bird();
$bird->fly();  // => パタパタ飛んだ！
trait は 直接 new できません！

Bird クラスが CanFly という機能を借りて使っているというイメージ。

まるで「レゴブロック」みたいに、クラスの中に機能を追加できます。
-->


### traitを使用するメリットとは
A.

7. traitを使用するメリットとは
コードの
再利用性：複数のクラスに同じ機能を提供できる。
柔軟性：継承に縛られず、機能だけを追加できる。

## その他

### TodoControllerクラスのコンストラクタはどのタイミングで実行されるか
A.ルートでコントローラが呼び出されたとき、コントローラがインスタンス化されるタイミング

<!-- 8. TodoControllerクラスのコンストラクタはどのタイミングで実行されるか
ルートでコントローラが呼び出されたとき、コントローラがインスタンス化されるタイミングで実行されます。ミドルウェアの設定などがここで行われます。 -->
<!-- ①ルートにアクセス
routes/web.php
Route::get('/todos/{id}/edit', [TodoController::class, 'edit']);

②ルートマッチング
ブラウザが /todos/1/edit にアクセス
→ Laravelが web.php の Route::get() を見て、どのコントローラが対応するか判断

[TodoController::class, 'edit']

$controller = new TodoController();  // ←★ここでコンストラクタ実行！
$controller->edit(1);

③コントローラーがインスタンス化される
ここで初めて Laravel が new TodoController() を実行します。

class TodoController extends Controller
{
    public function __construct()
    {
        // ←★この __construct が実行されるのはこのタイミング！
        $this->middleware('auth');
    }

    public function edit($id)
    {
        // ここでIDを使って編集画面を表示
    }
}
つまり、ルートにマッチした時点で、コントローラーのコンストラクタが呼ばれる -->

### RequestクラスからFormRequestクラスに変更した理由
A.
9. RequestクラスからFormRequestクラスに変更した理由
バリデーションロジックをコントローラから分離して、コードの可読性と再利用性を高めるためです。
FormRequestにバリデーションルールを定義できます。

### $errorsのhasメソッドの引数・返り値は何か
10. $errors->has()の引数・返り値は何か
引数：バリデーション対象のフィールド名（例：'title'）
返り値：そのフィールドにエラーがあるかどうか（true / false）エラーがなければ null が返ります。

### $errorsのfirstメソッドの引数・返り値は何か
A.
引数	バリデーション対象のフィールド名（例: 'title'）
返り値	最初のエラーメッセージ（文字列） or null
<!-- 11. $errors->first()の引数・返り値は何か
引数：バリデーション対象のフィールド名
返り値：そのフィールドに関する最初のエラーメッセージ（文字列）エラーがなければ null が返ります。 -->

<!-- $request->validate([
    'title' => 'required|max:10',
]);
これで title に何も入力しなかった場合、Laravelは以下のようなエラーを $errors に保持します：

$request->validate([
    'title' => 'required|max:10',
]);
これで title に何も入力しなかった場合、Laravelは以下のようなエラーを $errors に保持します： -->

<!-- ② Bladeテンプレートで使う：
blade
@if ($errors->has('title'))
    <p>{{ $errors->first('title') }}</p>
@endif
この場合、title フィールドに複数のエラーがあっても、一番最初のメッセージだけが表示されます。
つまり：
html
<p>タイトルは必須です。</p> -->


<!-- フィールドとは
<form action="/todos" method="POST">
    @csrf
    <input type="text" name="title">
    <textarea name="content"></textarea>
    <button type="submit">送信</button>
</form>
ここでのフィールド名は：
title
content -->
<!-- @if ($errors->has('title'))
    <p class="text-red-500">タイトルを入力してください。</p>
@endif -->

### フレームワークとは何か
12. フレームワークとは何か

アプリケーション開発の基本構造や共通処理を提供する土台です。再発明の必要がなくなり、保守性・拡張性も向上します。
標準化され、開発効率が上がります。
今回は、PHPの代表的なWebフレームワークとしてLaravelを学びました。

<!-- フレームワークがやってくれること
Laravelなどのフレームワークは、以下のような機能を「最初から用意」してくれます：

機能	       役割・意味
ルーティング	URLと処理を結びつける仕組み（routes/web.php）
コントローラー	リクエストを受けて処理する場所
ビュー（Blade）	画面を表示するテンプレート
バリデーション	入力チェック
データベース操作	ORM（Eloquent）で簡単に操作できる
セキュリティ対策	CSRF、暗号化、バリデーションなどが標準装備
エラーハンドリング	例外処理が統一されていて見やすい
ログ記録	エラーや処理の記録を簡単に取れる -->

<!-- メリットはたくさん：
時間短縮（共通処理が最初からある）
保守性が高い（ルールに沿った作りになってる）
チーム開発がしやすい（構造が共通）
セキュリティ対策が組み込まれている
初心者でも構造をマネしやすい -->

<!--
メリット:
効率化:
ゼロから開発するよりも短期間でシステムやアプリケーションを構築できます。
標準化:
コードの構造や機能が標準化され、保守やメンテナンスが容易になります。
品質向上:
定型的なプロセスに従い開発を進めることで、品質を向上させることができます。
共同開発:
組織内で共通のフレームワークを用いることで、開発チーム間の連携が円滑になります。
デメリット:
柔軟性の制限:
フレームワークに依存しすぎると、柔軟なカスタマイズが難しくなる場合があります。
学習コスト:
フレームワーク特有のコードや知識を習得する必要があります。
汎用性の限界:
特定の目的や課題に特化したフレームワークは、他の用途には適さない場合があります。﻿
-->


<!-- 補足：フレームワークとライブラリの違い
項目	フレームワーク	ライブラリ
主導権	フレームワークが持ってる	開発者が自由に使う
呼び出し方	フレームワークに組み込んで動く	自分で使いたいときに呼ぶ
例	Laravel, Rails, Spring	jQuery, Guzzle, Axios など -->


### MVCはどういったアーキテクチャか
A.
13. MVCはどういったアーキテクチャか

Model - View - Controller の略で、
「アプリケーションを 3つの役割 に分けて設計するアーキテクチャ（設計思想）」です。

MVC	役割	Laravelでのファイル例	担当内容
Model	データの管理（DBとのやり取り）	app/Models/Todo.php	データの取得・保存・更新など（Eloquent）
View	画面の表示	resources/views/*.blade.php	HTMLテンプレートの表示。変数を埋め込んで出力
Controller	処理の振り分け	app/Http/Controllers/TodoController.php	ユーザからの入力 (送信データ)に基づき、ModelやViewを制御する

<!-- Routing	ユーザからの依頼（リクエスト）に応じてどの処理を動かすかの判断を司る -->

### ORMとは何か、またLaravelが使用しているORMは何か
14. ORMとは何か、またLaravelが使用しているORMは何か
ORM（Object-Relational Mapping）は、「データベースのテーブル」と「プログラムのオブジェクト」を、 自動で結びつけてくれる仕組み
LaravelではEloquent ORMが使われています。

ORM	データベースをオブジェクト指向で扱う仕組みです。
Eloquent Laravelに標準搭載されているORM機能。Modelを使ってDB操作ができます。

SQLを知らなくても、PHPのオブジェクトだけでDBとやり取りできます。
メリット
SQLが最小限	生のSQLを書く量が減るので、コードがシンプルで安全
セキュリティ強化	プレースホルダなどでSQLインジェクションに強くなる
保守しやすい	DB設計の変更にも柔軟に対応できる（マイグレーションと連携）
可読性が高い	PHPらしいコードでDBを操作できるため、読みやすく直感的

<!--
プレースホルダとは？
▷ 一言で言うと：
SQL文の中で「値の代わり」に使う“穴”のこと。

sql
SELECT * FROM users WHERE name = ?;
上の ? がプレースホルダです。
あとから 値をバインド（結びつけ） します。

なぜ使うの？
「SQLインジェクション」を防ぐためです。

危険な書き方（プレースホルダなし）
php
$name = $_GET['name'];
$sql = "SELECT * FROM users WHERE name = '$name'"; // ← 直接埋め込んでる
たとえば $name = "' OR 1=1 --" だったら…

sql
SELECT * FROM users WHERE name = '' OR 1=1 --';
→ すべてのユーザーが取得されます。
これが SQLインジェクション の典型パターンです。

安全な書き方（プレースホルダあり）
php
$name = $_GET['name'];
$sql = "SELECT * FROM users WHERE name = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name]);
→ プレースホルダ ? に後から $name を安全にバインド

プレースホルダは「値として処理される」ので、
悪意あるSQLコードはただの文字列として扱われ、注入されません。

Laravelではどう？
Eloquent や Query Builder を使えば、
自動でプレースホルダを使ってくれるので、セキュリティ面で安全。

php
User::where('name', $name)->get();
↑ 中では以下のようなSQLになります：
sql
SELECT * FROM users WHERE name = ?; -->

### composer.json, composer.lockとは何か
15. composer.json, composer.lockとは何か
composer.json：使用したいパッケージやバージョンの指定を記述。
composer.lock：実際にインストールされたパッケージのバージョンと依存関係を記録。
チーム開発ではcomposer.lockを共有することで、環境の再現性が保たれます。

composer.lock とは？
実際に使う「確定バージョンの記録」
composer install をしたときに、自動で生成されるファイル。
composer.json に書かれた条件に合うパッケージの、正確なバージョン番号や依存関係が全部記録されます。
つまり、全開発者が 同じバージョンで動くように保証してくれるのが composer.lock の役割です。
<!--
プロジェクトの「設計書」みたいなもの
どんなパッケージ（ライブラリ）を使うかを定義するファイル。

開発者が手で編集することもあります。

例えば Laravel プロジェクトなら、こんな感じ：

json
コードをコピーする
{
  "require": {
    "laravel/framework": "^10.0",
    "guzzlehttp/guzzle": "^7.0"
  }
}
これは「Laravelフレームワーク10系以上、Guzzle 7系を使いますよ」といった指定です。


composer.lock：実際にインストールされたパッケージのバージョンと依存関係を記録。
チーム開発ではcomposer.lockを共有することで、環境の再現性が保たれます。

composer.lock とは？
実際に使う「確定バージョンの記録」
composer install をしたときに、自動で生成されるファイル。
composer.json に書かれた条件に合うパッケージの、正確なバージョン番号や依存関係が全部記録される。

例：
{
  "packages": [
    {
      "name": "laravel/framework",
      "version": "10.9.0",
      ...
    },
    ...
  ]
}
つまり、全開発者が 同じバージョンで動くように保証してくれるのが composer.lock の役割！

それぞれの違いまとめ
項目	composer.json	composer.lock
説明	パッケージの「希望バージョン」を定義	パッケージの「確定バージョン」を記録
編集する人	開発者が直接編集する	Composerが自動生成する
目的	どのライブラリを使いたいか指定	全員の環境で同じ動作を保証
共有の必要性	必須（Gitで共有）	必須（Gitで共有）←特にチーム開発では重要！ -->

### composerでインストールしたパッケージ（ライブラリ）はどのディレクトリに格納されるのか
A.vendor/ディレクトリに格納されます。ここにすべてのライブラリが入ります。
16. composerでインストールしたパッケージ（ライブラリ）はどのディレクトリに格納されるのか


<!-- vendorディレクトリとは？
Composerが自動で作るディレクトリ
外部のライブラリがすべてこの中に入る
Laravelのコアもここに入っている（例：laravel/framework）

作成されるタイミング	composer install や composer update 実行時 -->

<!-- そもそも
Composerとは、一言でいうと「PHPのパッケージ・ライブラリ管理ツール」です。
PHPを用いたアプリ開発に必要なライブラリやパッケージをまとめて管理することができます。

メリット
パッケージのインストールが楽にできる
チーム開発でパッケージやパッケージのバージョンを統一できる
パッケージの依存関係を考えなくていい

パッケージ管理ツールを使わない場合、必要なパッケージを全て手作業で一つずつインストールする必要があります。
一方パッケージ管理ツールを使えば、コマンド一発で必要なパッケージをすべてインストールすることができます。

チームでサービスを開発する場合、当たり前ですが、パッケージやパッケージのバージョンは統一する必要があります。 -->



<!-- Composerをインストールし、composer.jsonをアプリのディレクトリに配置してcomposer installをしたら、composer.jsonに書かれた情報を基にパッケージやライブラリがvendorディレクトリ配下にまとめてインストールされます。

このとき同時に生成されるのがcomposer.lockファイルです。composer install時に参照したパッケージやパッケージのバージョン情報がcomposer.lockに書き込まれます。

そして二回目以降はcomposer installをしたときには、composer.jsonではなくcomposer.lockを参照します。

つまり、composer.lockがある場合は、composer installをしても、単にcomposer.lockを参照してライブラリをインストールするだけで、何かパッケージの情報（パッケージの追加とかバージョン情報とか）が書き換わることはないのです。

Composerを用いてチーム開発する場合

要は、composer.lockファイルをGit管理してチーム間で共有すれば良いのです。 -->




<!-- ComposerはPHPのライブラリ・パッケージ管理ツール
composer installをすると、composer.jsonを基にパッケージがインストールされ、パッケージの情報が書かれたcomposer.lockが生成される
二回目以降は、composer installをするとcomposer.lockを基にパッケージがインストールされる（composer.jsonのパッケージ情報は書き変わらない）
パッケージを追加したい場合は、composer require パッケージ名を使用すると、composer.jsonにパッケージ情報が追加される＋composer.lockにもパッケージ情報が追加される
パッケージをアップデートしたい場合は、composer update パッケージ名を使用すると、パッケージ（composer.jsonの情報）が最新になる＋composer.lockにも最新の情報が記載される -->
