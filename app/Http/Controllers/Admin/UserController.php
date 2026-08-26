<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\MembershipPlan;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roleFilter = $request->input('role', 'student');
        
        $users = User::role($roleFilter)
            ->with(['roles', 'trainer'])
            ->orderBy('name', 'asc')
            ->get();

        $roles = Role::all();
        $trainers = User::role('trainer')->where('is_active', true)->get();
        $plans = MembershipPlan::active()->get();

        return view('admin.users.index', compact('users', 'roles', 'trainers', 'plans', 'roleFilter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'trainer_id' => 'nullable|exists:users,id',
            'objective' => 'nullable|string',
            'birth_date' => 'nullable|date',
            // Membership fields if student
            'plan_id' => 'nullable|exists:membership_plans,id',
            'start_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'objective' => $request->objective,
            'trainer_id' => $request->trainer_id,
            'is_active' => true,
        ]);

        $role = Role::findOrFail($request->role_id);
        $user->roles()->sync([$role->id]);

        // If user is a student and plan is selected, create a membership
        if ($role->name === 'student' && $request->plan_id) {
            $plan = MembershipPlan::findOrFail($request->plan_id);
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now();
            $endDate = $startDate->copy()->addDays($plan->duration_days);

            Membership::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'price_paid' => $plan->price,
                'created_by' => $request->user()->id,
            ]);
        }

        return redirect()->route('admin.users.index', ['role' => $role->name])
            ->with('success', '¡Usuario creado con éxito!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'trainer_id' => 'nullable|exists:users,id',
            'objective' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'objective' => $request->objective,
            'trainer_id' => $request->trainer_id,
            'is_active' => $request->is_active,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $primaryRole = $user->getPrimaryRole() ?? 'student';

        return redirect()->route('admin.users.index', ['role' => $primaryRole])
            ->with('success', '¡Usuario actualizado con éxito!');
    }
}
