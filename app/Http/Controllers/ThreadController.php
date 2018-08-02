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

        if (request()->wantsJson()) {
            return $threads;
        }

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
        return view('threads.show',[
            'thread'=>$thread,
            'replies'=>$thread->replies()->paginate(15)
        ]);
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
     * 删除帖子
     *
     * @param        $channel
     * @param Thread $thread
     *
     * @throws \Exception
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($channel,Thread $thread)
    {
        //应用授权策略
        $this->authorize('update',$thread);

        if ($thread->user_id != auth()->id()) {
            abort(403,'You do not have permission to do this');
        }

        $thread->delete();

        if (request()->wantsJson()) {
            return response([],204);
        }

        return redirect('/threads');
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
        $threads = Thread::with('channel')->latest()->filter($filters);

        //是否存在分类
        if ($channel->exists) {
            $threads = $threads->where('channel_id',$channel->id);
        }

        return $threads->get();
    }
}
