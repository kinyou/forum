<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        //1.创建一个登陆的用户
        $this->actingAs(factory(User::class)->create());
        //2.点击发布按钮创建一个新的帖子
        $thread = factory(Thread::class)->make();
        $this->post('/threads',$thread->toArray());
        //3.然后访问帖子,可以成功访问到刚才创建的帖子
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }


    /**
     * @test
     */
    public function guests_may_not_create_threads()
    {
        $this->expectException(AuthenticationException::class);

        $thread = factory(Thread::class)->make();

        $this->post('/threads',$thread->toArray());
    }
}
