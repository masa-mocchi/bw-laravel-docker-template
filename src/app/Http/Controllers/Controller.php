<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

// AuthorizesRequests
// 認可（＝ユーザーに操作権限があるか）をチェックする機能。

// 例: $this->authorize('update', $post);

// DispatchesJobs
// ジョブ（バックグラウンドで動かす処理）を呼び出す機能。

// 例: $this->dispatch(new SendEmailJob($user));

// ValidatesRequests
// リクエストのバリデーションを簡単に書けるようにする。

// 例: $this->validate($request, ['title' => 'required|max:255']);

