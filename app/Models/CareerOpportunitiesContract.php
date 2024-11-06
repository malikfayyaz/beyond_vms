<?php

namespace App\Models;

use App\Http\Controllers\Admin\CareerOpportunitiesSubmissionController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunitiesContract extends Model
{
    protected $table = 'career_opportunities_contract';
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];
    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager_id', 'id');
    }
    public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class, 'career_opportunity_id' , 'id');
    }
    public function submission()
    {
        return $this->belongsTo(CareerOpportunitySubmission::class, 'submission_id' , 'id');
    }
    public function contractNotes()
    {
        return $this->hasMany(ContractNote::class, 'contract_id', 'id'); // Adjust the foreign key if necessary
    }
    public function workOrder()
    {
        return$this->belongsTo(CareerOpportunitiesWorkorder::class,'workorder_id','id');
    }
    public function workorderBackground()
    {
        return $this->hasOne(WorkorderBackground::class, 'workorder_id', 'workorder_id');
    }
    public function getDateRangeAttribute()
    {
        $start = $this->start_date ? Carbon::parse($this->start_date)->format('m/d/Y') : '';
        $end = $this->end_date ? Carbon::parse($this->end_date)->format('m/d/Y') : '';

        return $start && $end ? "$start - $end" : '';
    }
    public function consultant()
    {
        return $this->belongsTo(Consultant::class, 'candidate_id', 'id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function contractRates()
    {
        return $this->hasOne(ContractRate::class, 'contract_id', 'id');
    }
    public static function getContractStatus($statusId)
    {
        switch ($statusId) {
            case 0:
                return 'Draft';
            case 1:
                return 'Active';
            case 2:
                return 'Cancelled';
            case 3:
                return 'Approved';
            case 4:
                return 'Waiting For Supplier Approval';
            case 6:
                return 'Terminated';
            default:
                return 'danger N/A';
        }
    }

    public function reasonClose() {
        return $this->belongsTo(Setting::class, 'termination_reason', 'id');
    }

    public function getFormattedTerminationDateAttribute()
    {
        return Carbon::parse($this->termination_date)->format('m/d/Y');
    }

    public function extensionRequest() {
        return $this->hasMany(ContractExtensionRequest::class, 'contract_id', 'id');
    }
    public function contractRateEditRequest() {
        return $this->hasMany(ContractRateEditRequest::class, 'contract_id', 'id');
    }
    public function contractAdditionalBudgetRequest() {
        return $this->hasMany(ContractAdditionalBudget::class, 'contract_id', 'id');
    }
    public function ContractBudgetWorkflow()
    {
        return $this->hasMany(ContractBudgetWorkflow::class, 'contract_id', 'id');
    }
    

    public function latestApprovedExtensionRequest()
    {
        return $this->hasMany(ContractExtensionRequest::class, 'contract_id', 'id')
                    ->where('ext_status', 1)
                    ->latest()
                    ->first();
    }
}
