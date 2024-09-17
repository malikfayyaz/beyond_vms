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
        'first_name', 'last_name', 'email', 'phone', 'role_id', 'country_id', 'status', 'profile_image', 'member_access'
    ];

    /**
     * Relationship with the Role model.
     * Assuming `member_access` is the foreign key for roles.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'member_access', 'id'); // Replace with your foreign key if different
    }

    /**
     * Relationship with the Country model.
     * Assuming `country_id` is the foreign key for countries.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
