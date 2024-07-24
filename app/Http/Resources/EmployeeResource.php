<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->user;
        $role = $this->user->roles->first();

        return [
            'id' => $this->id,
            'image' => $user->image,
            'username' => $user->name,
            'email' => $user->email,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'phone_number' => $this->phone_number,
            'ssn' => $this->ssn,
            'city' => $this->city->name,
            'state_id' => $this->state_id,
            'state' => $this->state->name,
            'address' => $this->address,
            'role' => $role->name,
            'role_id' => $role->id,
            'employable' => $this->employable->name,
            'employable_type' => $this->employable_type,
            'employable_id' => $this->employable_id,
            'is_banned' => $this->when($request->user()->can('employee.ban'), $user->isBanned()),
            'ban_expired_at' => $user->bans()->latest()->first()->expired_at ?? null
        ];
    }
}
