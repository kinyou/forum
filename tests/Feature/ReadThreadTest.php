<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->thread = create(Thread::class);
    }

    /**
     * @test
     */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertStatus(200)
            ->assertSeeText($this->thread->title);

    }


    /**
     * @test
     */
    public function a_user_can_read_a_single_thread()
    {

        $this->get($this->thread->path())
            ->assertStatus(200)
            ->assertSeeText($this->thread->title);
    }


    /**
     * @test
     */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        //1.如果存在Thread
        $reply = create(Reply::class,['thread_id'=>$this->thread->id]);
        //2.并且该Thread拥有回复
        //3.那么当我们看到Thread时也能看到回复

        $this->get($this->thread->path())
            ->assertStatus(200)
            ->assertSeeText($reply->body);
    }


    /**
     * @test
     */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class,['channel_id'=>$channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }


    /**
     * @test
     */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create(User::class,['name'=>'xingmaogou']));

        $threadByXingmaogou = create(Thread::class,['user_id'=>auth()->id()]);
        $threadNotByXingmaogou = create(Thread::class);

        $this->get('threads?by=xingmaogou')
            ->assertSee($threadByXingmaogou->title)
            ->assertDontSee($threadNotByXingmaogou->title);
    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_by_popularity()
    {
        //
        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class,['thread_id'=>$threadWithTwoReplies->id],2);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class,['thread_id'=>$threadWithThreeReplies->id],3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popularity=1')->json();

        $this->assertEquals([3,2,0],array_unique(array_column($response,'replies_count')));
    }
}
