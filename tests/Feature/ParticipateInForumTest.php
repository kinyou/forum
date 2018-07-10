<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * @test
     */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        //1.创建一个拥有权限的用户 be方法是登陆用户
        $this->signIn(create(User::class));
        //2.并且存在一个帖子
        $thread = create(Thread::class);
        //3.当用户给帖子添加回复的时候
        $reply = make(Reply::class);
        $this->post($thread->path() . '/replies',$reply->toArray());
        //4.那么回复应该在当前页面可见
        $this->get($thread->path())
            ->assertSee($reply->body);

    }


    /**
     * @test
     */
    public function unauthenticated_user_may_no_add_replies()
    {
        $thread = create(Thread::class);
        $reply = create(Reply::class);

        //未登陆的用户进行回复,跳转到登陆页面
        $this->withExceptionHandling()
            ->post($thread->path() . '/replies',$reply->toArray())
            ->assertRedirect('/login');
    }


    /**
     * @test
     */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn(create(User::class));

        $thread = create(Thread::class);
        $reply = make(Reply::class,['body'=>null]);

        $this->post($thread->path() . '/replies',$reply->toArray())
            ->assertSessionHasErrors('body');
        
    }
}
