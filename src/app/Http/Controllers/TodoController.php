<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Todo;

class TodoController extends Controller
{
    private $todo;

    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }


    public function index()
    {
        
        $todos = $this->todo->all();
    
        return view('todo.index', ['todos' => $todos]);
    }

    public function create()
    {
        return view('todo.create');
    }

    public function show($id)
    {
        $todo = $this->todo->find($id);
        return view('todo.show', ['todo' => $todo]);
    }

    public function store(TodoRequest $request)
    {
        $inputs = $request->all();
        $this->todo->fill($inputs);
        $this->todo->save();
        return redirect()->route('todo.index');
    }

    public function edit($id)
    {
        $todo = $this->todo->find($id);
        return view('todo.edit', ['todo' => $todo]);
    }

    public function update(TodoRequest $request, $id)
    {
        $inputs = $request->all();
        $todo = $this->todo->find($id);
        $todo->fill($inputs);
        $todo->save();
        return redirect()->route('todo.show', $todo->id);
    }
    
            // dd($inputs);
        // array:3 [▼
        // "_token" => ""
        // "_method" => "PUT"
        // "content" => ""
        // ]
}




// データの流れ（store）
// フォームから content が送られる
// Request クラスが受け取る
// validate() でチェック（バリデーション）
// 新しい Todo モデルを生成
// content を代入
// save() でDBに保存
// 一覧ページ（todo.index）にリダイレクト

// [1] ユーザーがフォームに「やること」を入力して送信
//      ↓
// [2] POST /todo に飛ぶ（Route）
//      ↓
// [3] TodoController@store 実行
//      ↓
// [4] バリデーションチェック
//      ↓
// [5] OKならTodoモデルに保存
//      ↓
// [6] リダイレクトで一覧ページへ
//      ↓
// [7] index() でDBから全件取得 → Bladeに渡す
//      ↓
// [8] index.blade.php で一覧表示

// Laravel のコントローラー TodoController を定義しており、
// 「ToDoリストアプリ」の基本的な CRUD のうち、
// 一覧表示・作成フォーム・保存処理を実装しています。

        // dd($todos);
        // TodoModelを介して、todosテーブルのデータの全件取得を確認できたためddは削除（var_dampの機能）


    // 5. store() メソッド – フォームの保存処理
    // $request->all() で POSTデータをすべて取得。
    // $todo->fill() は、$fillable がモデルに設定されている必要があります（マスアサインメント）。
    // 保存後、todo.index（ルート名）にリダイレクトします。
    // Request $request	フォーム送信データを受け取る Laravelのヘルパー。型は Illuminate\Http\Request
    // $inputs = $request->all();の内容
    // all()	フォームから送られてきた全データを**連想配列（array）**で取得
    // $inputs	例えば、['content' => '牛乳を買う'] みたいな形になる
    // 備考：もし CSRFトークン（_token）や他の隠しフィールドが含まれてたら、それも入る。
    // だから、後で使う fill() に安全性のために $fillable をちゃんと定義しとく
    // new Todo()	モデルの新しいインスタンスを作成。中身はまだ空っぽ
    // $todo = new Todo();の内容
    // new Todo()	モデルの新しいインスタンスを作成。中身はまだ空っぽ
    // $todo	App\Todo クラスのインスタンス。$todo instanceof Todo は true
    // Laravelの Request クラスは、フォームから送信されたデータや、
    // リクエストに関する情報（URL・ヘッダー・IPなど） をまとめて持ってる便利なオブジェクト。

    // メソッドの引数に渡してインスタンス化する方法
    // メソッドインジェクションという。
    // dd($request->all());
    // array:2 [▼
    // "_token" => "0PX8lvqTqEbpXD13E9foxdMAccVgmDpnK08iGA0a"
    // "content" => "dd($request->all());"
    // ]

    // dd($inputs);
    // array:2 [▼
    // "_token" => "0PX8lvqTqEbpXD13E9foxdMAccVgmDpnK08iGA0a"
    // "content" => "dd($inputs);"
    // ]
    // ->fill()は$todo->{連想配列のkey} = {連想配列のvalue}を配列の全ての要素に対して行ってくれます。