<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register($request): array
    {
        $user = User::query()->create([
            'name' => $request['name'] ?? null,
            'username' => $request['username'],
            'phone_number' => $request['phone_number'] ?? null,
            'address' => $request['address'] ?? null,
            'ssn' => $request['ssn'] ?? null,
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $userRole = Role::query()->where('name', $request['role'])->first();
        $user->assignRole($userRole);

        // Assign permissions associated with the role to the user
        $permissions = $userRole->permissions()->pluck('name')->toArray();
        $user->givePermissionTo($permissions);

        // Load the user's roles and permissions
        $user->load('roles', 'permissions');

        // Reload the user instance to get updated roles and permissions
        $user = User::query()->find($user['id']);
        $user = $this->appendRolesAndPermissions($user);
        $user['token'] = $user->createToken('token')->plainTextToken;

        $message = 'User created successfully.';
        $code = 201;
        return ['user' => $user, 'message' => $message, 'code' => $code];
    }

    public function login($request): array
    {
        $user = User::query()
            ->where('email', $request['email'])
            ->first();
        if (!is_null($user)) {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                $message = 'user email & password does not match with our record.';
                $code = 401;
            } else {
                $user = $this->appendRolesAndPermissions($user);
                $user['token'] = $user->createToken('token')->plainTextToken;
                $message = 'User logged in successfully.';
                $code = 200;
            }
        } else {
            $message = 'User not found.';
            $code = 404;
        }
        return ['user' => $user, 'message' => $message, 'code' => $code];
    }

    public function logout(): array
    {
        $user = Auth::user();
        if (!is_null(Auth::user())) {
            Auth::user()->currentAccessToken()->delete();
            $message = 'User logged out successfully.';
            $code = 200;
        } else {
            $message = 'invalid token.';
            $code = 404;
        }
        return ['user' => $user, 'message' => $message, 'code' => $code];
    }

    private function appendRolesAndPermissions($user)
    {
        $roles = [];
        foreach ($user->roles as $role) {
            $roles[] = $role->name;
        }
        unset($user['roles']);
        $user['roles'] = $roles;
        $permissions = [];
        foreach ($user->permissions as $permission) {
            $permissions[] = $permission->name;
        }
        unset($user['permissions']);
        $user['permissions'] = $permissions;

        return $user;
    }

    public function showProfile(): array
    {

        $userId = Auth::user()->getAuthIdentifier();
        $user = new UserResource(User::find($userId));
        $message = __('messages.show_success', ['class' => __('user')]);
        $code = 200;
        return ['data' => $user, 'message' => $message, 'code' => $code];
    }


    public function updateProfile($request): array
    {
        $userId = Auth::user()->getAuthIdentifier();
        $user = User::find($userId);
        $image = ImageService::update($request, $user);

        $user->update([
            'image' => $image,
            'name' => $request['username'] ?? $user['name'],
            'email' => $request['email'] ?? $user['email'],
        ]);

        $employee = new UserResource($user);
        $message = __('messages.update_success', ['class' => __('user')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }
}
