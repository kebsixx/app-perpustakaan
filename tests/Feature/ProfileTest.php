<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profil_dan_ganti_password(): void
    {
        $this->get('/profil')->assertRedirect('/login');

        $user = User::factory()->create(['password' => Hash::make('password'), 'role' => 'petugas']);

        $this->actingAs($user)->get('/profil')
            ->assertOk()->assertSee($user->name)->assertSee($user->email)->assertSee('Petugas');

        $this->put('/profil/password', ['password_lama' => 'salah', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123'])
            ->assertSessionHasErrors('password_lama');
        $this->put('/profil/password', ['password_lama' => 'password', 'password' => 'pendek', 'password_confirmation' => 'pendek'])
            ->assertSessionHasErrors('password');
        $this->put('/profil/password', ['password_lama' => 'password', 'password' => 'rahasia123', 'password_confirmation' => 'beda12345'])
            ->assertSessionHasErrors('password');

        $this->put('/profil/password', ['password_lama' => 'password', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123'])
            ->assertRedirect('/profil')->assertSessionHas('success');

        $this->assertTrue(Hash::check('rahasia123', $user->fresh()->password));
    }
}
