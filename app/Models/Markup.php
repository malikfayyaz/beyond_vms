<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markup extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'location_id', 'category_id', 'markup_value', 'status'
    ];

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id'); 
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(JobTemplates::class, 'category_id', 'id');
    }

}
