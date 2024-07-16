<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeCollection;
use Illuminate\Support\Facades\Auth;

class EmployeeService
{
    public function index(): array
    {
        $employee = new EmployeeCollection(Employee::nonAdmin()->paginate());
        $message = __('messages.index_success', ['class' => __('employees')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $employee = DB::transaction(function () use ($request): Employee {
            $image = ImageService::store($request);

            $user = User::query()->create([
                'image' => $image,
                'name' => $request['username'],
                'email' => $request['email'],
                'password' => $request['password'],
            ]);

            $user->assignRole(Role::find($request['role_id']));

            $employee = Employee::query()->create([
                'full_name' => $request['full_name'],
                'gender' => $request['gender'],
                'birthday' => $request['birthday'],
                'phone_number' => $request['phone_number'],
                'address' => $request['address'],
                'ssn' => $request['ssn'],
                'user_id' => $user['id'],
                'state_id' => $request['state_id'],
                'employable_type' => $request['employable_type'],
                'employable_id' => $request['employable_id']
            ]);

            return $employee;
        });

        $employee = new EmployeeResource($employee);
        $message = __('messages.store_success', ['class' => __('employee')]);
        $code = 201;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }

    public function show(Employee $employee): array
    {
        $employee = new EmployeeResource($employee);
        $message = __('messages.show_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }

    public function update($request, Employee $employee): array
    {
        $employee = DB::transaction(function () use ($request, $employee): Employee {
            $user = $employee->user;
            $image = ImageService::update($request, $user);
            $user->update([
                'image' => $image,
                'name' => $request['username'] ?? $user['name'],
                'email' => $request['email'] ?? $user['email'],
            ]);

            if (isset($request['role_id'])) {
                $user->syncRoles();
                $user->assignRole(Role::find($request['role_id']));
            }

            $employee->update([
                'full_name' => $request['full_name'] ?? $employee['full_name'],
                'gender' => $request['gender'] ?? $employee['gender'],
                'birthday' => $request['birthday'] ?? $employee['birthday'],
                'phone_number' => $request['phone_number'] ?? $employee['phone_number'],
                'address' => $request['address'] ?? $employee['address'],
                'ssn' => $request['ssn'] ?? $employee['ssn'],
                'state_id' => $request['state_id'] ?? $employee['state_id'],
                'employable_type' => $request['employable_type'] ?? $employee['employable_type'],
                'employable_id' => $request['employable_id'] ?? $employee['employable_id']
            ]);

            return $employee;
        });

        $employee = new EmployeeResource($employee);
        $message = __('messages.update_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }

    public function showProfile()
    {
        $userId = Auth::user()->getAuthIdentifier();
        $employee = Employee::where('user_id', $userId)->first();

        $employee = new EmployeeResource($employee);
        $message = __('messages.show_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }


    public function updateProfile($request)
    {
        $userId = Auth::user()->getAuthIdentifier();
        $employee = Employee::where('user_id', $userId)->first();

        $employee = DB::transaction(function () use ($request, $employee): Employee {
            $user = $employee->user;
            $image = ImageService::update($request, $user);

            $user->update([
                'image' => $image,
                'name' => $request['username'] ?? $user['name'],
                'email' => $request['email'] ?? $user['email'],
            ]);

            $employee->update([
                'full_name' => $request['full_name'] ?? $employee['full_name'],
                'gender' => $request['gender'] ?? $employee['gender'],
                'birthday' => $request['birthday'] ?? $employee['birthday'],
                'phone_number' => $request['phone_number'] ?? $employee['phone_number'],
                'address' => $request['address'] ?? $employee['address'],
                'ssn' => $request['ssn'] ?? $employee['ssn'],
                'state_id' => $request['state_id'] ?? $employee['state_id'],
            ]);

            return $employee;
        });

        $employee = new EmployeeResource($employee);
        $message = __('messages.update_success', ['class' => __('employee')]);
        $code = 200;
        return ['data' => $employee, 'message' => $message, 'code' => $code];
    }
}
