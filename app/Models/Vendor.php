<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Models\Country;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'phone', 'role_id', 'country', 'status', 'profile_image', 'member_access','user_id'
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ?? '') . ' ' . $this->last_name);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'member_access', 'id'); // Replace with your foreign key if different
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
