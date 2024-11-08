<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareerOpportunitiesInterview;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesContract;
use App\Models\CareerOpportunity;
use App\Models\JobTemplates;
use App\Models\TemplateRatecard;
use App\Models\GenericData;
use App\Models\DivisionBranchZoneConfig;


class CommonController extends Controller
{
    public function rejectInterview(Request $request,$id) 
    {
        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        
        if ($sessionrole === 'admin') {
           $role = 1;
        } else if ($sessionrole === 'client') {
            $role = 2;
        } else if ($sessionrole === 'vendor') {
            $role = 3;
        }

        $user_id =  checkUserId($userid,$sessionrole);

        $validateData = $request->validate([
            'reschedule_reason' => 'required|int',
            'rejection_note' => 'required|string|max:250',
        ]);

        $interview = CareerOpportunitiesInterview::findOrFail($id);

        $interview->reason_rejection = $validateData['reschedule_reason'];
        $interview->notes = $validateData['rejection_note'];
        $interview->interview_acceptance_date = null; 
        $interview->acceptance_notes = null; 
        $interview->status = 3;
        $interview->rejected_by = $user_id;       
        $interview->rejected_type = $role; 
        $interview->interview_cancellation_date = now();
        $interview->save();

        $successMessage = 'Interview rejected successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.interview.index")  // Redirect URL for AJAX
        ]);
    }

    public function rejectCandidate(Request $request,$id) 
    { 
        $validateData = $request->validate([
            'cand_rej_reason' => 'required|int',
            'cand_rej_note' => 'required|string|max:250',
        ]);

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        
        if ($sessionrole === 'admin') {
           $role = 1;
        } else if ($sessionrole === 'client') {
            $role = 2;
        } else if ($sessionrole === 'vendor') {
            $role = 3;
        }

        $user_id =  checkUserId($userid,$sessionrole);
        
        $interview = CareerOpportunitiesInterview::findOrFail($id);
        $submission = CareerOpportunitySubmission::findOrFail($interview->submission_id);

        $interview->reason_rejection = $validateData['cand_rej_reason'];
        $interview->notes = $validateData['cand_rej_note'];
        $interview->interview_acceptance_date = null; 
        $interview->acceptance_notes = null; 
        $interview->status = 3;
        $interview->rejected_by = $user_id;       
        $interview->rejected_type = $role; 
        $interview->interview_cancellation_date = now();
        $interview->save();

        $submission->reason_for_rejection = $validateData['cand_rej_reason'];
        $submission->note_for_rejection = $validateData['cand_rej_note'];
        $submission->resume_status = 6;
        $submission->rejected_by = $user_id;
        $submission->rejected_type = $role;
        $submission->date_rejected = now();
        $submission->save();

        $successMessage = 'Interview & candidate rejected successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.interview.index")  // Redirect URL for AJAX
        ]);
    }

    public function closeAssignmentTemp(Request $request,$id) 
    {
        $validateData = $request->validate([
            'close_contr_reason' => 'required|int',
            'close_contr_note' => 'required|string|max:250',
        ]);

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        
        if ($sessionrole === 'admin') {
           $role = 1;
        } else if ($sessionrole === 'client') {
            $role = 2;
        } else if ($sessionrole === 'vendor') {
            $role = 3;
        }

        $user_id =  checkUserId($userid,$sessionrole);
        // dd($validateData);
        
        $contract = CareerOpportunitiesContract::findOrFail($id);
        $CareerOpportunity = CareerOpportunity::findOrFail($contract->career_opportunity_id);

        $contract->status = 2;
        $contract->termination_status = 2;
        $contract->termination_reason = $validateData['close_contr_reason'];
        $contract->termination_notes = $validateData['close_contr_note'];
        $contract->term_by_id = $user_id;
        $contract->term_by_type = $role;
        $contract->termination_date = now();
        $contract->save();

        $successMessage = 'Contract terminated successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.contracts.show", $contract->id)  // Redirect URL for AJAX
        ]);
    }
    
    public function openContract(Request $request,$id) 
    {
        $contract = CareerOpportunitiesContract::findOrFail($id);
        $sessionrole = session('selected_role');

        $contract->status = 1;
        $contract->termination_status = 0;
        $contract->termination_reason = null;
        $contract->termination_notes = null;
        $contract->term_by_id = null;
        $contract->term_by_type = null;
        $contract->termination_date = null;

        $contract->save();

        $successMessage = 'Contract open-back successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.contracts.show", $contract->id)
        ]);
        
    }

    public function jobDetails($id)
    {
        // Retrieve the job with the count of submissions
        $job = CareerOpportunity::withCount(['submissions', 'interviews', 'offers', 'workorders', 'hired'])
        ->with(['careerOpportunitiesBu.buName']) // Eager load the relationship
        ->findOrFail($id);
   
        // dd($job->hired);
        $initialJobData = [
            'id' => $job->id ?? null,
            'title' => $job->title ?? 'Default Job Title',
            'hiring_manager' => $job->hiringManager->full_name ?? 'No Client Name',
            'created_at' => $job->created_at_formatted, 
            'submission_count' => $job->submissions_count,
            'interview_count' => $job->interviews_count, 
            'interview_count' => $job->interviews_count, 
            'workorders_count' => $job->workorders_count, 
            'hired_count' => $job->hired_count, 
            'offers_count' => $job->offers_count, 
            'jobstatus' => CareerOpportunity::getStatus($job->jobStatus), 
            'location' => $job->location->location_details, 
            'opening' => $job->num_openings, 
            'category' => $job->category->title, 
            'expense' => $job->expenses_allowed, 
            'days' => $job->day_per_week, 
            'hours' => $job->hours_per_day, 
            'jobDuration' => $job->date_range, 
            'ratetype' => $job->paymentType->title, 
            'min_rate' => '$' . number_format($job->min_bill_rate, 2), 
            'max_rate' => '$' . number_format($job->max_bill_rate, 2), 
            'careerOpportunitiesBu' => $job->careerOpportunitiesBu->map(function ($bu) {
                return [
                    'id' => $bu->id,
                    'bu_unit' => $bu->buName->name ?? 'Unknown BU', // Assuming 'name' is the column in GenericData
                    'percentage' => $bu->percentage,
                ];
            }),
            ];
    
        return response()->json(['data' => $initialJobData]);
    }

    public function submissionDetails($id)
    {
        $submission = CareerOpportunitySubmission::findOrFail($id);

        $contract = $submission->contracts()->where('status', 1)->latest()->first();
        // dd($contract->careerOpportunity->title);   
        // dd(CareerOpportunitiesContract::getContractStatus($contract->status));
        if ($contract) {
            $contract_title = $contract->careerOpportunity->title;
            $date_range = $contract->date_range;
            $contract_status = CareerOpportunitiesContract::getContractStatus($contract->status);
        }else{
            $contract_title = null;
            $date_range = null;
            $contract_status = null;
        }

        $initialJobData = [
            'id' => $submission->id ?? null,
            'subStatus' => CareerOpportunitySubmission::getSubmissionStatus($submission->resume_status),
            'vendor' => $submission->vendor->full_name,
            'email' => $submission->consultant->user->email,
            'location' => $submission->location->location_details, 
            'vendor_rate' => '$' . number_format($submission->vendor_bill_rate, 2), 
            'overtimer_rate' => '$' . number_format($submission->client_over_time_rate, 2), 
            'client_rate' => '$' . number_format($submission->bill_rate, 2), 
            'date_range' => $date_range, 
            'contract_title' => $contract_title, 
            'contract_status' => $contract_status, 
         ];

        return response()->json(['data' => $initialJobData]);
    }

    public function loadMarketJobTemplate($labourType,$type)
    {
        // Query the JobTemplate model
            $jobTemplates = JobTemplates::where([
                ['cat_id', $labourType],
                ['profile_worker_type_id', $type],
                ['status', 'Active']
            ])->get(['id', 'job_title']);

            // Map the results to rename 'job_title' as 'name'
            $formattedTemplates = $jobTemplates->map(function ($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->job_title,
                ];
            });
       // Return JSON response
        return response()->json($formattedTemplates);
    }

    
    // For loading job template
    public function loadJobTemplate(Request $request){
        $id = $request->input('template_id');
        $level_id = $request->input('level_id');
        $jobTemplate = JobTemplates::find($id);
        $response = [];




            $response['job_description'] =  $jobTemplate->job_description;
            $response['job_family_id'] = $jobTemplate->job_family_id;
            $response['cat_id'] = $jobTemplate->cat_id;
            $response['worker_type'] = $jobTemplate->worker_type_id;
            $response['job_code'] = $jobTemplate->job_code;
            if($level_id > 0) {
            // $location_id = $_REQUEST['location'];
            $template_rates = TemplateRatecard::where('template_id', $id)
            ->where('level_id', $level_id)
            ->first();
            $response['min_bill_rate'] = $template_rates->min_bill_rate;
            $response['max_bill_rate'] = $template_rates->bill_rate;
            $response['currency'] = $template_rates->currency_id;
            // $currencySetting = Setting::model()->findByPk($template_rates->currency);
            // $response['currency_class'] = $currencySetting->value;
            }
        echo json_encode($response);
    }

    public function divisionLoad(Request $request)
    {
        $id = $request->input('bu_id');

        $response = [
            'zone' => '<option  value="" > Select Option</option>',
            'branch' => '<option  value=""> Select Option</option>',
            'division' => '<option  value=""> Select Option</option>',
        ];

        // Fetch job branches
        $jobBranches = DivisionBranchZoneConfig::whereIn('bu_id', [$id])
            ->where('status', 'active')
            ->distinct()
            ->pluck('branch_id');

        foreach ($jobBranches as $branchId) {
            $jobBranch = GenericData::where('id', $branchId)
                ->where('status', 'active')
                ->first();

            if ($jobBranch) {
                $response['branch'] .= '<option data-id="' . $jobBranch->id . '" value="' . $jobBranch->id . '">' . $jobBranch->name . '</option>';
            }
        }

        // Fetch job zones
        $jobZones = DivisionBranchZoneConfig::whereIn('bu_id', [$id])
            ->where('status', 1)
            ->distinct()
            ->pluck('zone_id');

        foreach ($jobZones as $zoneId) {
            $jobZone = GenericData::where('id', $zoneId)
                ->where('status', 'active')
                ->first();

            if ($jobZone) {
                $response['zone'] .= '<option data-id="' . $jobZone->id . '" value="' . $jobZone->id . '">' . $jobZone->name . '</option>';
            }
        }

        // Fetch job divisions
        $jobDivisions = DivisionBranchZoneConfig::whereIn('bu_id', [$id])
            ->where('status', 1)
            ->distinct()
            ->pluck('division_id');

        foreach ($jobDivisions as $devisionId) {
            $jobDivision = GenericData::where('id', $devisionId)
                ->where('status', 'active')
                ->first();

            if ($jobDivision) {
                $response['division'] .= '<option data-id="' . $jobDivision->id . '" value="' . $jobDivision->id . '">' . $jobDivision->name . '</option>';
            }
        }

        return response()->json($response);
    }
    
}
