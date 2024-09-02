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
                            <a href=""
                       @click="deleteItem($row->id)"
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
        // Validate and update the catalog item
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $catalog = Catalog::findOrFail($id);
        $catalog->name = $request->input('name');
        $catalog->description = $request->input('description');
        $catalog->price = $request->input('price');
        $catalog->save();

        return redirect()->route('admin.catalog.index')->with('success', 'Catalog item updated successfully.');
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
}
