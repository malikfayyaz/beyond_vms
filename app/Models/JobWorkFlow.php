<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobWorkFlow extends Model
{
    use HasFactory;

    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id'); 
    }

    public function approvalRole()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id'); 
    }
}
