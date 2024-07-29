<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mchev\Banhammer\Traits\Bannable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the employee associated with the user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    // Define the relationship for buy orders
    public function buyOrders()
    {
        return $this->employee->employable->buyOrders()
            ->where('orderable_from_type', Warehouse::class);
    }

    // Define the relationship for sell orders
    public function sellOrders()
    {
        return $this->employee->employable->sellOrders();
    }

    // Define the relationship for sales
    public function sales()
    {
        return $this->employee->employable->sales();
    }

    // Define the relationship for destructions
    public function destructions()
    {
        return $this->employee->employable->destructions();
    }

    public function manufacturerOrders()
    {
        return $this->employee->employable->buyOrders()
            ->where('orderable_from_type', Manufacturer::class);
    }
}
