<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTeammember extends Model
{
    protected $table = 'vendor_teammembers';
    public $timestamps = false;
    protected $fillable = ['vendor_id', 'teammember_id'];
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function teammember()
    {
        return $this->belongsTo(Vendor::class, 'teammember_id', 'id');
    }
}
