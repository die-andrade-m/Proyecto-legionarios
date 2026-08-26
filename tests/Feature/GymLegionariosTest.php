<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\MembershipPlan;
use App\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymLegionariosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & plans
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\MembershipPlanSeeder::class);
        $this->seed(\Database\Seeders\AchievementSeeder::class);
        $this->seed(\Database\Seeders\ExerciseSeeder::class);
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_student_can_access_student_dashboard()
    {
        $studentRole = Role::where('name', 'student')->first();
        $student = User::factory()->create();
        $student->roles()->sync([$studentRole->id]);

        // Create an active membership for them
        $plan = MembershipPlan::first();
        Membership::create([
            'user_id' => $student->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active',
            'price_paid' => $plan->price,
        ]);

        $response = $this->actingAs($student)->get('/dashboard');
        
        // Router should redirect to student/dashboard
        $response->assertRedirect('/student/dashboard');

        // Check if student/dashboard responds with 200
        $dashboardResponse = $this->actingAs($student)->get('/student/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee($student->name);
    }

    public function test_trainer_can_access_trainer_dashboard()
    {
        $trainerRole = Role::where('name', 'trainer')->first();
        $trainer = User::factory()->create();
        $trainer->roles()->sync([$trainerRole->id]);

        $response = $this->actingAs($trainer)->get('/dashboard');
        $response->assertRedirect('/trainer/dashboard');

        $dashboardResponse = $this->actingAs($trainer)->get('/trainer/dashboard');
        $dashboardResponse->assertStatus(200);
    }
}
