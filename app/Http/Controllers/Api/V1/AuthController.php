<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();

        $tokenName = 'api-token-' . now()->format('YmdHis');

        $token = $user->createToken($tokenName, $this->getAbilitiesForRole($user->role))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => new UserResource($user->load('employee.department')),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:admin,manager,employee,developer,administrative,team-lead,client,visitor',
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = $request->input('role', 'employee');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        if ($request->has('position') || $request->has('department_id')) {
            $employeeData = [
                'name' => $request->name,
                'email' => $request->email,
                'position' => $request->position,
                'department_id' => $request->department_id,
                'hire_date' => $request->hire_date,
                'salary' => $request->salary,
            ];

            $user->employee()->create($employeeData);
            $user->load('employee.department');
        }

        $token = $user->createToken('register-token', $this->getAbilitiesForRole($role))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'user' => new UserResource($user->load('employee.department')),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user()->loadMissing('employee.department');

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ], 200);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        $token = $user->createToken('refreshed-token', $this->getAbilitiesForRole($user->role))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    private function getAbilitiesForRole(string $role): array
    {
        return match ($role) {
            'admin' => ['*'],
            'manager' => ['*'],
            default => ['read:own'],
        };
    }
}
