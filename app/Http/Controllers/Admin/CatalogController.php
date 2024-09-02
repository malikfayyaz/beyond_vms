<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\JobTemplates;
use App\Models\TemplateRatecard;
use Yajra\DataTables\Facades\DataTables;

class CatalogController extends BaseController
{
    /**
     * Display a listing of the catalog.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JobTemplates::query();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
       
                            $btn = ' <a href=""
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('admin.catalog.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>
                            <a href="#"
                       @click="deleteItem(' .$row->id. ')"
                       class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-trash"></i>
                     </a>';
      
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        // Logic to get and display catalog items
        return view('admin.job.catalog.index'); // Assumes you have a corresponding Blade view
    }

    /**
     * Show the form for creating a new catalog item.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.job.catalog.create', [
            'job' => [],'ratecards' => []
        ]); // Assumes you have a corresponding Blade view
    }

    /**
     * Store a newly created catalog item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'cat_id' => 'required|integer', // Assuming 'categories' is your table and 'id' is the primary key
            'profile_worker_type_id' => 'required|integer', // Assuming 'worker_types' is your table and 'id' is the primary key
            'worker_type_id' => 'required|integer', // Assuming 'worker_types' is your table and 'id' is the primary key
            'job_code' => 'required', // Assuming 'jobs' is your table and 'job_code' is a unique field
            'job_family_id' => 'required|integer', // Assuming 'job_families' is your table and 'id' is the primary key
            'status' => 'required|string|in:Active,Inactive', // Validate that status is either 'Active' or 'Inactive'
        ]);

          // Decode the JSON string to an array
          $jobCatalogRateCardsJson = $request->input('jobCatalogRateCards');
          $jobCatalogRateCards = json_decode($jobCatalogRateCardsJson, true);


        // Add additional data
        $additionalData = [
            'created_by_id' => auth()->id(), // or $request->user()->id
            'created_from' => 'Program', // Static value
            'job_description'=>$request->job_description,
        ];

        // Merge the validated data with the additional data
        $dataToSave = array_merge($validatedData, $additionalData);

        // Save the data to the JobTemplates model
        $catalog = new JobTemplates($dataToSave);
        $catalog->save();
        $modelId = $catalog->id;
       

        if (!empty($jobCatalogRateCards) && is_array($jobCatalogRateCards)) {
            foreach ($jobCatalogRateCards as $key => $rateCardData) {
                // Check if the record already exists
                $existingRecordCount = TemplateRatecard::where([
                    ['level_id', '=', $rateCardData['jobLevel']],
                    ['template_id', '=', $modelId]
                ])->count();
        
                if ($existingRecordCount > 0) {
                    continue;
                }
        
                // Create a new instance of TemplateRatecard
                $rateCardModel = new TemplateRatecard();
                $rateCardModel->template_id = $modelId;
                $rateCardModel->level_id = $rateCardData['jobLevel'];
                $rateCardModel->currency = $rateCardData['currency'];
                $rateCardModel->bill_rate = str_replace(",", "", $rateCardData['maxBillRate']);
                $rateCardModel->min_bill_rate = str_replace(",", "", $rateCardData['minBillRate']);
                // $rateCardModel->date_created = now();
        
                // Ensure default values if empty
                $rateCardModel->bill_rate = ($rateCardModel->bill_rate == '') ? 0.00 : $rateCardModel->bill_rate;
                $rateCardModel->min_bill_rate = ($rateCardModel->min_bill_rate == '') ? 0.00 : $rateCardModel->min_bill_rate;
        
                // Save the model
                if (!$rateCardModel->save()) {
                    // Handle save failure, if needed
                    Log::error('Failed to save Catalog', ['rateCardModel' => $rateCardModel]);
                }
            }
        }
        $successMessage = 'Job catalog is added successfully!';
        session()->flash('success', $successMessage);
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect_url' => url()->previous() // Redirect back URL for AJAX
            ]);

        // return redirect()->route('admin.catalog.index')->with('success', 'Catalog item created successfully.');
    }

    /**
     * Display the specified catalog item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Logic to show a specific catalog item
        $job = JobTemplates::findOrFail($id);
        return view('admin.catalog.show', compact('job'));
    }

    /**
     * Show the form for editing the specified catalog item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Logic to get the catalog item to edit
        $job = JobTemplates::findOrFail($id);
        $ratecards  =  $job->templateratecard;
       
         // Format the rate cards data if necessary
        $ratecardsArray = $ratecards->map(function ($ratecard) {
            return [
                'jobLevel' => $ratecard->level_id,
                'minBillRate' => $ratecard->min_bill_rate,
                'maxBillRate' => $ratecard->bill_rate,
                'currency' => $ratecard->currency,
            ];
        });
        // dd($ratecardsArray);
      
        return view('admin.job.catalog.create', [
            'job' => $job,'ratecards' => $ratecardsArray
        ] );
    }

    /**
     * Update the specified catalog item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        // Validate and update the catalog item
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'cat_id' => 'required|integer', // Assuming 'categories' is your table and 'id' is the primary key
            'profile_worker_type_id' => 'required|integer', // Assuming 'worker_types' is your table and 'id' is the primary key
            'worker_type_id' => 'required|integer', // Assuming 'worker_types' is your table and 'id' is the primary key
            'job_code' => 'required', // Assuming 'jobs' is your table and 'job_code' is a unique field
            'job_family_id' => 'required|integer', // Assuming 'job_families' is your table and 'id' is the primary key
            'status' => 'required|string|in:Active,Inactive', // Validate that status is either 'Active' or 'Inactive'
        ]);

        // Decode the JSON string to an array
        $jobCatalogRateCardsJson = $request->input('jobCatalogRateCards');
        $jobCatalogRateCards = json_decode($jobCatalogRateCardsJson, true);
        $additionalData = [
           
            'job_description' => $request->job_description,
        ];

        // Merge the validated data with the additional data
        $dataToUpdate = array_merge($validatedData, $additionalData);

        // Find the existing JobTemplates record and update it
        $catalog = JobTemplates::findOrFail($id);
        $catalog->update($dataToUpdate);

        // Remove existing rate cards associated with this template
        TemplateRatecard::where('template_id', $catalog->id)->delete();

        if (!empty($jobCatalogRateCards) && is_array($jobCatalogRateCards)) {
            foreach ($jobCatalogRateCards as $key => $rateCardData) {
                // Check if the record already exists (for future edits where rate cards need to be preserved)
                $existingRecordCount = TemplateRatecard::where([
                    ['level_id', '=', $rateCardData['jobLevel']],
                    ['template_id', '=', $catalog->id]
                ])->count();
    
                if ($existingRecordCount > 0) {
                    continue;
                }
    
                // Create a new instance of TemplateRatecard
                $rateCardModel = new TemplateRatecard();
                $rateCardModel->template_id = $catalog->id;
                $rateCardModel->level_id = $rateCardData['jobLevel'];
                $rateCardModel->currency = $rateCardData['currency'];
                $rateCardModel->bill_rate = str_replace(",", "", $rateCardData['maxBillRate']);
                $rateCardModel->min_bill_rate = str_replace(",", "", $rateCardData['minBillRate']);
    
                // Ensure default values if empty
                $rateCardModel->bill_rate = ($rateCardModel->bill_rate == '') ? 0.00 : $rateCardModel->bill_rate;
                $rateCardModel->min_bill_rate = ($rateCardModel->min_bill_rate == '') ? 0.00 : $rateCardModel->min_bill_rate;
    
                // Save the model
                if (!$rateCardModel->save()) {
                    // Handle save failure, if needed
                    Log::error('Failed to save Catalog', ['rateCardModel' => $rateCardModel]);
                }
            }
        }
       

                $successMessage = 'Job catalog is updated successfully!';
                session()->flash('success', $successMessage);
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect_url' => route('admin.catalog.index') // Redirect back URL for AJAX
                ]);
      

    }

    /**
     * Remove the specified catalog item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Logic to delete the catalog item
        $catalog = Catalog::findOrFail($id);
        $catalog->delete();

        return redirect()->route('admin.catalog.index')->with('success', 'Catalog item deleted successfully.');
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
            $response['currency'] = $template_rates->currency;
            // $currencySetting = Setting::model()->findByPk($template_rates->currency);
            // $response['currency_class'] = $currencySetting->value;
            }

        

        

        echo json_encode($response);
    }
}
