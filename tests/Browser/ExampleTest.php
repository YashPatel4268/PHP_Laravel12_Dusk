<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
   public function test_basic_example(): void
   {
       $user = User::factory()->create();

       $this->browse(function (Browser $browser) use ($user) {
           $browser->loginAs($user)
                   ->visit('/dashboard')
                   ->assertPathBeginsWith('/dashboard');
       });
   }
}
