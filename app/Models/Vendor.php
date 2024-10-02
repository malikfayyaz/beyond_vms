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
        'first_name', 'last_name', 'phone', 'role_id', 'country', 'status','profile_status', 'profile_image', 'member_access','user_id','organization',
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ?? '') . ' ' . $this->last_name);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function getVendorIdByUserId($userId)
    {
        $vendor = self::where('user_id', $userId)->first();
        return $vendor ? $vendor->id : null;
    }

}
