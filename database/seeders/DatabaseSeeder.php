<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Exercise;
use App\Models\MembershipPlan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MembershipPlanSeeder::class,
            AchievementSeeder::class,
            ExerciseSeeder::class,
            DemoUserSeeder::class,
        ]);
    }
}
