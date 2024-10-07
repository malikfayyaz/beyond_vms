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

    public function approveRejectBy()
    {
        return $this->belongsTo(User::class, 'approve_reject_by', 'id'); 
    }

    public function approvalRole()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id'); 
    }
}
