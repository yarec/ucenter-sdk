<?php

namespace Tests\Unit;

use Tests\TestCase;
use Thinker\DomainService;
use Thinker\Facades\UCenterApi;
use Thinker\Models\AccessToken;
use Thinker\Models\User;

class UserTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

        $user = new User([
            'id' => 123,
            'username' => 'chen.d',
            'email' => 'chen@ding.com',
            'phone' => 456,
        ]);
        $user->hold(new AccessToken(['access_token' => '789']));
        $this->user = $user;
    }

    public function test_props_are_correctly_set()
    {
        $this->assertEquals(123, $this->user->id);
        $this->assertEquals('chen.d', $this->user->username);
        $this->assertEquals('chen@ding.com', $this->user->email);
        $this->assertEquals(456, $this->user->phone);
    }

    public function test_it_returns_access_token_model()
    {
        $this->assertInstanceOf(AccessToken::class, $this->user->accessToken());
    }

    public function test_it_returns_access_token_string()
    {
        $this->assertEquals(789, $this->user->access_token);
    }

    public function test_it_may_reload_users_profile()
    {
        UCenterApi::fake();
        $userData = UCenterApi::getUser();

        $this->user->fresh();

        $this->assertEquals($userData->username, $this->user->username);
    }

    public function test_it_may_update_props()
    {
        UCenterApi::fake();
        $userData = UCenterApi::updateUser();
        
        $this->user->update([
            'username' => 'updated name',
            'email' => 'updated email',
        ]);

        $this->assertEquals($userData->username, $this->user->username);
        $this->assertEquals($userData->email, $this->user->email);
    }

    public function test_it_returns_domain_service()
    {
        $this->assertInstanceOf(DomainService::class, $this->user->domains());
    }

}
