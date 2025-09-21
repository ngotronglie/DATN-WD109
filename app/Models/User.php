<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
        'is_active',
        'address',
        'phone_number',
        'date_of_birth',
        'avatar',
    ];
        public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship với Favorite - User có nhiều favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Relationship với Product thông qua Favorite - User có nhiều products yêu thích
     */
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
        // Quan hệ với Address
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');   
}
    public function defaultAddress()
{
    return $this->hasOne(Address::class)->where('is_default', 1);
}

    // Phương thức để lấy địa chỉ mặc định của người dùng
    public function getDefaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    // Phương thức để lấy tất cả địa chỉ của người dùng
    public function getAllAddresses()
    {
        return $this->addresses()->get();
    }
    

}