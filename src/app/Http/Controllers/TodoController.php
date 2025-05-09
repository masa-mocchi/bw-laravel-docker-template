<?php

namespace App\Http\Controllers;

// 追加
use App\Todo;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        // 追加
        $todo = new Todo();
        $todos = $todo->all();
        // dd($todos);
        // TodoModelを介して、todosテーブルのデータの全件取得を確認できたためddは削除（var_dampの機能）

        return view('todo.index', ['todos' => $todos]);
        // [blade内での変数名 => 代入したい値]
    }

    public function create()
    {
        return view('todo.create');
    }

    public function store(Request $request)
    {
        $content = $request->input('content');

        // 1. todosテーブルの1レコードを表すTodoクラスをインスタンス化
        $todo = new Todo();
        // 2. Todoインスタンスのカラム名のプロパティに保存したい値を代入
        $todo->content = $content;
        // 3. Todoインスタンスの`->save()`を実行してオブジェクトの状態をDBに保存するINSERT文を実行
        $todo->save();

        return redirect()->route('todo.index');
    }

}