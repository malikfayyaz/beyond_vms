<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ?? '') . ' ' . $this->last_name);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function race() {
        return $this->belongsTo(Setting::class, 'ethnicity', 'id');
    }
    public function genDer() {
        return $this->belongsTo(Setting::class, 'gender', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($consultant) {
            // Generate the candidate_id using the 'WK' prefix and pad the id with leading zeros
            // The id is not available until after saving, so we simulate the next id
            $lastConsultant = Consultant::orderBy('id', 'desc')->first();
            $nextId = $lastConsultant ? $lastConsultant->id + 1 : 1;

            $consultant->candidate_id = 'WK' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }


}
