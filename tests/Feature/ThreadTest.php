<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_user_can_view_all_threads()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get('/threads');

        $response->assertStatus(200)->assertSeeText($thread->title);

    }


    /**
     * @test
     */
    public function a_user_can_read_a_single_thread()
    {

        $thread = factory(Thread::class)->create();

        $response = $this->get('/threads/' . $thread->id);

        $response->assertStatus(200)->assertSeeText($thread->title);
    }
}
