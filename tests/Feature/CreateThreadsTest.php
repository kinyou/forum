<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
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
        $this->signIn(create(User::class));
        //2.点击发布按钮创建一个新的帖子
        $thread = make(Thread::class);
        $response = $this->post('/threads',$thread->toArray());
        //2.1$response 是TestResponse的实例,而$response->headers->get('location')可以等到一个
        //http://localhost/threads/consectetur/1这样的url
        //3.然后访问帖子,可以成功访问到刚才创建的帖子
        $this->get($response->headers->get('location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }


    /**
     * @test
     */
    public function guests_may_not_see_the_create_thread_page()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function a_thread_requires_a_title()
    {
        //验证要填充的字段
        $this->publishThread(['title'=>null])
            ->assertSessionHasErrors('title');
    }


    /**
     * @test
     */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body'=>null])
            ->assertSessionHasErrors('body');
    }


    /**
     * @test
     */
    public function a_thread_requires_a_channel()
    {
        factory(Channel::class,2)->create();

        $this->publishThread(['channel_id'=>null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id'=>-99])
            ->assertSessionHasErrors('channel_id');
    }

    /**
     * @test
     * @return void
     */
    public function a_thread_can_be_deleted()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = create(Reply::class,['thread_id'=>$thread->id]);

        $response = $this->json('DELETE',$thread->path());
        $response->assertStatus(204);

        //判断threads表中是否有对应的数据
        $this->assertDatabaseMissing('threads',['id'=>$thread->id]);
        $this->assertDatabaseMissing('replies',['id'=>$reply->id]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $response = $this->delete($thread->path());

        $response->assertRedirect('/login');
    }

    /**
     * 提取的公共方法
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn(create(User::class));
        $thread = make(Thread::class,$overrides);
        return $this->post('/threads',$thread->toArray());

        
    }

}
