<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    /**
     * Test login and dashboard access.
     *
     * @return void
     */

public function test_login_and_dashboard()
{
    $user = \App\Models\User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('123456'),
    ]);

    $this->browse(function ($browser) use ($user) {
        $browser->visit('/login')
                ->type('email', $user->email)       // name="email"
                ->type('password', '123456')        // name="password"
                ->press('Login')
                ->waitForText('Dashboard', 10)
                ->assertSee('Dashboard');
    });
}


}
