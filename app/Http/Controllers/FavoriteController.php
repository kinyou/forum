<?php

namespace App\Http\Controllers;

use App\Reply;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 保存点赞
     * @param Reply $reply
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Reply $reply)
    {
        $reply->favorite();
        //跳回原来的地址
        return back();
    }
}
