<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        // dd('Hello World!');
        return view('todo.index'); // 修正
    }
}