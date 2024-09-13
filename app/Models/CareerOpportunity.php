<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerOpportunity extends Model
{
    use SoftDeletes;
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];
    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager', 'user_id');
    }
    public function workerType()
    {
        return $this->belongsTo(Setting::class, 'worker_type_id', 'id');
    }
    public function careerOpportunitiesBu()
    {
        return $this->hasMany(CareerOpportunitiesBu::class, 'career_opportunity_id');
    }
    public function division()
    {
        return $this->belongsTo(GenericData::class, 'division_id', 'id');
    }
    public function regionZone()
    {
        return $this->belongsTo(GenericData::class, 'region_zone_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(GenericData::class, 'branch_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Setting::class, 'cat_id', 'id');
    }
    public function businessReason()
    {
        return $this->belongsTo(Setting::class, 'hire_reason_id', 'id');
    }
    public function jobType()
    {
        return $this->belongsTo(Setting::class, 'type_of_job', 'id');
    }
    public function glCode()
    {
        return $this->belongsTo(Setting::class, 'gl_code_id', 'id');
    }
    public function paymentType()
    {
        return $this->belongsTo(Setting::class, 'payment_type', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(GenericData::class, 'currency_id', 'id');
    }
    public function careerBU()
    {
        return $this->hasOne(CareerOpportunitiesBu::class, 'career_opportunity_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
