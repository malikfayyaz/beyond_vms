<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferWorkFlow extends Model
{
    protected $table = 'offer_work_flows';
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];
    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
