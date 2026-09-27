<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('تسجيل الدخول إلى النظام');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'student@university.edu',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'student@university.edu',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'student@university.edu',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'student@university.edu',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_quick_login_works_for_demo(): void
    {
        $user = User::factory()->create([
            'name' => 'إلياس جمال الساده',
            'role' => 'student',
        ]);

        $response = $this->get("/quick-login/{$user->id}");

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_can_register_as_new_student(): void
    {
        $response = $this->post('/register', [
            'name' => 'طالب جديد مسجل',
            'email' => 'newstudent@university.edu',
            'role' => 'student',
            'university_id' => '44109999',
            'department' => 'هندسة البرمجيات',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'newstudent@university.edu',
            'role' => 'student',
        ]);
        $this->assertTrue(auth()->user()->isStudent());
    }

    public function test_guest_can_register_as_new_supervisor(): void
    {
        $response = $this->post('/register', [
            'name' => 'مشرف أكاديمي جديد',
            'email' => 'newsupervisor@university.edu',
            'role' => 'supervisor',
            'university_id' => 'PROF-999',
            'department' => 'هندسة البرمجيات',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'newsupervisor@university.edu',
            'role' => 'supervisor',
        ]);
        $this->assertTrue(auth()->user()->isSupervisor());
    }
}
