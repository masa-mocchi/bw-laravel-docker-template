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
        // TodoModelを介して、todosテーブルのデータの全件取得を確認できたためddは削除

        return view('todo.index');
    }
}

