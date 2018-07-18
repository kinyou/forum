<?php

namespace Tests\Feature;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function au_authenticated_user_can_favorite_any_reply()
    {
        //用户登录
        $this->signIn(create(User::class));

        $reply  = create(Reply::class);

        $this->post('replies/' . $reply->id . '/favorites' );

        $this->assertCount(1,$reply->favorites);
    }

    /**
     * @test
     */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function au_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn(create(User::class));

        $reply = create(Reply::class);

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $exception) {
            $this->fail('Did not expect to insert the same record set twice.');
        }

        $this->assertCount(1,$reply->favorites);
    }
}
