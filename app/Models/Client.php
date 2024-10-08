<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email', 'profile_image', 'role','user_id','phone',
        'country', 'description', 'profile_status', 'business_name', 'organization'
    ];
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ?? '') . ' ' . $this->last_name);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
            ->wherePivot('model_type', 'App\Models\User')
            ->wherePivot('model_id', $this->user_id);  // This ensures it uses the user_id
    }

    public function scopeByStatus($query)
    {
        return $query->where('profile_status', '1')
                     ->get();
    }
    public static function getClientIdByUserId($userId)
    {
        $client = self::where('user_id', $userId)->first();
        return $client ? $client->id : null;
    }
    public function careerOpportunitiesOffers()
    {
        return $this->hasMany(CareerOpportunitiesOffer::class, 'hiring_manager_id', 'id');
    }

}
