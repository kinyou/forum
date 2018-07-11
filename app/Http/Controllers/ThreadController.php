<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadsFilters;
use App\Thread;
use App\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        //只有保存需要登录
        $this->middleware('auth')->except(['show','index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel,ThreadsFilters $filters)
    {

        $threads = $this->getThreads($channel,$filters);

        return view('threads.index',compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //对提交数据进行验证
        $this->validate($request,[
            'title'=>'required',
            'channel_id'=>'required|exists:channels,id',
            'body'=>'required'
        ]);

        $thread = Thread::create([
            'user_id'=>auth()->id(),
            'channel_id'=>$request->input('channel_id'),
            'title'=>$request->input('title'),
            'body'=>$request->input('body')
        ]);

        return redirect($thread->path());
    }

    /**
     * 显示帖子详细
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($channelId,Thread $thread)
    {
        return view('threads.show',compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }

    /**
     * 获取对应的搜索条件帖子
     * @param Channel $channel
     * @param ThreadsFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel,ThreadsFilters $filters)
    {
        //使用了本地作用域特性
        $threads = Thread::latest()->filter($filters);

        //是否存在分类
        if ($channel->exists) {
            $threads = $threads->where('channel_id',$channel->id);
        }

        return $threads->get();
    }
}
