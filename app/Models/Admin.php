<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Models\Country;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'phone', 'member_access', 'country', 'admin_status', 'profile_image','member_access','user_id'
    ];


    public function role()
    {
        return $this->belongsTo(Role::class, 'member_access', 'id'); // assuming `member_access` is the foreign key for roles
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ?? '') . ' ' . $this->last_name);
    }
    public static function getAdminIdByUserId($userId)
    {
        $admin = self::where('user_id', $userId)->first();
        return $admin ? $admin->id : null;
    }
}
