<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_check_if_user_is_an_admin()
    {
        $userAdmin = User::factory()->create([
            'name' => 'Fulan',
            'email' => 'fulan@gmail.com',
        ]);

        $userGuest = User::factory()->create([
            'name' => 'Guest',
            'email' => 'guest@gmail.com',
        ]);

        $this->assertTrue($userAdmin->isAdmin());
        $this->assertFalse($userGuest->isAdmin());
    }
}
