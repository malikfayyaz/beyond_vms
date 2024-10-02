<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTeammember extends Model
{
    use HasFactory;

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function teammember()
    {
        return $this->belongsTo(Vendor::class, 'teammember_id', 'id');
    }
}
