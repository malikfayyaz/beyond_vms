<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Models\Client;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'approval_role_id',
        'hiring_manager_id',
        'approval_required',
        'approval_number'
    ];

    // Define relationships
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id'); 
    }

    public function approvalRole()
    {
        return $this->belongsTo(Setting::class, 'approval_role_id', 'id');
    }

    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager_id', 'id');
    }
}
