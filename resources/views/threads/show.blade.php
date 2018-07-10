@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#">{{$thread->author->name}}</a> 发表了:
                        {{$thread->title}}
                    </div>
                    <div class="panel-body">{{$thread->body}}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        @if(auth()->check())
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form action="{{$thread->path() . '/replies'}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" rows="5" placeholder="说点什么吧...."></textarea>
                    </div>

                    <button type="submit" class="btn btn-default">提交</button>
                </form>

            </div>
        </div>
        @else
        <p class="text-center">
            请先 <a href="{{route('login')}}">登陆</a>,然后再发表回复
        </p>

        @endif
    </div>
@endsection