@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#">{{$thread->author->name}}</a> 发表了:
                        {{$thread->title}}
                    </div>
                    <div class="panel-body">{{$thread->body}}</div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{$replies->links()}}

                @if(auth()->check())
                <form action="{{$thread->path() . '/replies'}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" rows="5" placeholder="说点什么吧...."></textarea>
                    </div>

                    <button type="submit" class="btn btn-default">提交</button>
                </form>
                @else
                <p class="text-center">
                    请先 <a href="{{route('login')}}">登陆</a>,然后再发表回复
                </p>
                 @endif
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            <a href="#">{{$thread->author->name}}</a>发布于{{$thread->created_at->diffForHumans()}}
                            当前共有 {{$thread->replies()->count()}} 个回复
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection