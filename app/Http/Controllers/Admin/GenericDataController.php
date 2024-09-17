<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GenericData;
use App\Models\JobFamilyGroupConfig;
use App\Models\DivisionBranchZoneConfig;
use App\Models\Location;
use App\Models\Country;
use App\Models\State;
use App\Models\Setting;
use App\Models\SettingCategory;
use App\Models\Vendor;
use App\Models\JobTemplates;
use App\Models\Markup;
use App\Models\Client;
use App\Models\Workflow;
use Spatie\Permission\Models\Role;

class GenericDataController extends BaseController
{

    public function manageData(Request $request, $formtype = null)
    {
       
        $countries = Country::all();
       
            $fields = $request->route()->defaults['fields'] ?? [];
            // dd($fields);
        if ($request->isMethod('get')) {
           
            $data = GenericData::with(['country','setting'])->where('type', $formtype)->get();

            return view('admin.data_points.manage', [
                'formtype' => $formtype,
                'data' => $data,
                'fields' => $fields,
                'countries' => $countries,
               
                
            ]);
        } elseif ($request->isMethod('post')) {
            // Get all the input fields from the request
            $input = $request->all();
            
            // Rename 'country' to 'country_id' and 'symbol' to 'symbol_id' in the input data
            if (isset($input['country'])) {
                $input['country_id'] = $input['country'];
                unset($input['country']); // Remove the original 'country' field
            }

            if (isset($input['symbol'])) {
                $input['symbol_id'] = $input['symbol'];
                unset($input['symbol']); // Remove the original 'symbol' field
            }
           
            $validatedData =$input;

            
           
            // dd($validatedData, array_keys($input));
            // dd($validatedData);
            // Check if the request has an 'id' to determine whether to create or update
            if ($request->has('id') && $request->input('id')) {
                // Update the existing record
                $data = GenericData::find($request->input('id'));
                $successMessage = ucfirst(str_replace('-', ' ', $formtype)) . ' updated successfully!';
                session()->flash('success', $successMessage); // Store success message in session
                if ($data) {
                    $data->update(array_merge($validatedData, ['type' => $formtype]));
                    return response()->json([
                        'success' => true,
                        'message' => $successMessage,
                        'redirect_url' => url()->previous() // Redirect back URL for AJAX
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => $successMessage,
                        'redirect_url' => url()->previous() // Redirect back URL for AJAX
                    ]);
                }
            } else {
                // $fields = array_keys($request->route()->defaults['fields']);
                // Handle POST request: Save or update data
                // $validatedData = $request->only($fields);
                // Create a new record
                GenericData::create(array_merge($validatedData, ['type' => $formtype]));
                

                // Set a success message in the session
                $successMessage = ucfirst(str_replace('-', ' ', $formtype)) . ' saved successfully!';
                session()->flash('success', $successMessage); // Store success message in session
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect_url' => url()->previous() // Redirect back URL for AJAX
                ]);
            }
          
        }
    }

    public function jobGroupConfig(Request $request)
    {
        if ($request->isMethod('get')) {
            // Retrieve all JobFamilyGroupConfig entries
            $data = JobFamilyGroupConfig::with(['jobFamily', 'jobFamilyGroup'])->get();
            
            
        
            // Pass data to the view
            return view('admin.data_points.job_group_config', [
                'data' => $data,
            ]);
        } elseif ($request->isMethod('post')) {
            // Validate request data
            $validatedData = $request->validate([
                'job_family_id' => 'required|exists:generic_data,id',
                'job_family_group_id' => 'required|exists:generic_data,id'
            ]);

            if ($request->has('id') && $request->input('id')) {
                // Update existing JobFamilyGroupConfig entry
                $data = JobFamilyGroupConfig::find($request->input('id'));
                session()->flash('success', 'Job Family Group configuration updated successfully!');
                if ($data) {
                    $data->update($validatedData);
                    return response()->json([
                        'success' => true,
                        'message' => 'Job Family Group configuration updated successfully!',
                        'redirect_url' => url()->previous()
                    ]);
                }
            } else {
                // Create new JobFamilyGroupConfig entry
                JobFamilyGroupConfig::create($validatedData);
                session()->flash('success', 'Job Family Group configuration saved successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Job Family Group configuration saved successfully!',
                    'redirect_url' => url()->previous()
                ]);
            }
        }
    }


    public function divisionBranchZoneConfig(Request $request)
    {
        if ($request->isMethod('get')) {
            // Handle GET request: Show form and data
            $data = DivisionBranchZoneConfig::with(['division', 'branch', 'zone', 'bu'])->get(); // Fetch all config data


            return view('admin.data_points.bu_config', [
                'data' => $data,
              
            ]);
        } elseif ($request->isMethod('post')) {
            // Validation rules
            $rules = [
                'division_id' => 'required|exists:generic_data,id',
                'branch_id' => 'required|exists:generic_data,id',
                'zone_id' => 'required|exists:generic_data,id',
                'bu_id' => 'required|exists:generic_data,id',
                'status' => 'required|in:Active,Inactive',
            ];

            // Custom error messages (optional)
            $messages = [
                'division_id.exists' => 'The selected division is invalid.',
                'branch_id.exists' => 'The selected branch is invalid.',
                'zone_id.exists' => 'The selected zone is invalid.',
                'bu_id.exists' => 'The selected business unit is invalid.',
                // Add more custom messages as needed
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ]);
            }

            $validatedData = $validator->validated(); // Get validated data

            // Check if the request has an 'id' to determine whether to create or update
            if ($request->has('id') && $request->input('id')) {
                // Update the existing record
                $config = DivisionBranchZoneConfig::find($request->input('id'));
                $successMessage = 'Configuration updated successfully!';

                if ($config) {
                    $config->update($validatedData);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Configuration not found!',
                    ]);
                }
            } else {
                // Create a new record
                DivisionBranchZoneConfig::create($validatedData);
                $successMessage = 'Configuration saved successfully!';
            }

            // Set a success message in the session and return a JSON response
            session()->flash('success', $successMessage);
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect_url' => url()->previous() // Redirect back URL for AJAX
            ]);
        }
    }

    

    public function locationDetail(Request $request)
    {
        // dd($request);
        if ($request->isMethod('get')) {
            // Handle GET request: Show form and data
            $data = Location::with(['country', 'state'])->get(); // Fetch all location data
            $countries = Country::all();
            return view('admin.data_points.location', [
                'data' => $data,
                'countries' => $countries,
            ]);
        } elseif ($request->isMethod('post')) {
            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'address1' => 'nullable|string|max:30',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'city' => 'required|string|max:100',
                'zip_code' => 'required|string|max:8',
                'status' => 'required|in:Active,Inactive',
            ];
    
            // Custom error messages (optional)
            $messages = [
                'country_id.exists' => 'The selected country is invalid.',
                'state_id.exists' => 'The selected state is invalid.',
                // Add more custom messages as needed
            ];
    
            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ]);
            }
    
            $validatedData = $validator->validated(); // Get validated data
            // dd( $validatedData);
            // Check if the request has an 'id' to determine whether to create or update
            if ($request->has('id') && $request->input('id')) {
                // Update the existing record
                $location = Location::find($request->input('id'));
                $successMessage = 'Location updated successfully!';
    
                if ($location) {
                    $location->update($validatedData);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Location not found!',
                    ]);
                }
            } else {
                // Create a new record
                Location::create($validatedData);
                $successMessage = 'Location saved successfully!';
            }
    
            // Set a success message in the session and return a JSON response
            session()->flash('success', $successMessage);
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect_url' => url()->previous() // Redirect back URL for AJAX
            ]);
        }
    }

    public function getStates($countryId)
    {
        // Fetch states based on the country ID
        $states = State::where('country_id', $countryId)->get(['id', 'name']);

        // Return the states as a JSON response
        return response()->json($states);
    }

    public function settingDetail(Request $request)
    {
        // dd($request);
        if ($request->isMethod('get')) {
            $setting_category = SettingCategory::all()->map(function($item) {
                return ['id' => $item->id, 'name' => $item->name];
            });
            return view('admin.data_points.setting', [
                'setting_category' => $setting_category,
            ]);
        }elseif ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|max:255',
               
            ];
    
            // Custom error messages (optional)
            $messages = [
               'name' => 'The Category field is Required.',
                // Add more custom messages as needed
            ];
    
            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all(),
                ]);
            }
            $validatedData = $validator->validated(); // Get validated data

            SettingCategory::create($validatedData);
            $successMessage = 'Category saved successfully!';
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect_url' => url()->previous() // Redirect back URL for AJAX
            ]);

        }
    }

    public function workflow(Request $request)
    {
        // $data = GenericData::where('type', 'busines-unit')->get();
        $data = Client::where('profile_status',1)->get();

        return view('admin.workflow.index', compact('data'));
    }

    public function workflowEdit($id)
    {
        $client_data = Client::find($id);

        $clients = Client::where('profile_status', 1)->where('id', '!=', $id)->get();

        $roles = Role::where('id',2)->get();

        $table_data = Workflow::with(['client', 'approvalRole', 'hiringManager'])->get();

        // Fetch the item to edit
        $item = GenericData::findOrFail($id);
        
        // Return the edit view with the item data
        return view('admin.workflow.edit', compact('item','client_data','clients','roles','table_data'));
    }

    public function workflowStore(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'approval_role_id' => 'required|exists:roles,id',
            'hiring_manager_id' => 'required|exists:clients,id',
            'approval_required' => 'required|in:yes,no',
        ]);
        // dd($validatedData);

        $workflow = new Workflow($validatedData);
        $workflow->save();
        

        $successMessage = 'Workflow updated successfully!';
        $redirectUrl = route('admin.workflow');

        // Redirect to a specific route or return a response
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => $redirectUrl // Redirect back URL for AJAX
        ]);
    }

    public function settingMarkup(Request $request)
    {
       // dd($request);
        if ($request->isMethod('get')) {
            $data = Markup::with('vendor', 'location', 'category')->get(); 
            $vendors = Vendor::all();
            $categories = JobTemplates::all();
            $locations = Location::all();

            return view('admin.data_points.markup', [
                'data' => $data,
                'vendors' => $vendors,
                'categories' => $categories,
                'locations' => $locations,
            ]);
        }elseif ($request->isMethod('post')) {
            $rules = [
                'vendor_id'   => 'required|string|max:255',
                'location_id' => 'required|string|max:255',
                'category_id' => 'required|string|max:255',
                'markup_value'=> 'required|string|max:255',
                'status'      => 'required|string|max:255',
               
            ];
    
            // Custom error messages (optional)
            $messages = [
               'vendor_id'    => 'The Vendor field is Required.',
               'location_id'  => 'The Location field is Required.',
               'category_id'  => 'The Category field is Required.',
               'markup_value' => 'The Markup  field is Required.',
               'status'       => 'The Status field is Required.',
                // Add more custom messages as needed
            ];
    
            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all(),
                ]);
            }
            $validatedData = $validator->validated(); // Get validated data

            if ($request->has('id') && $request->input('id')) {
                // Update the existing record
                $location = Markup::find($request->input('id'));
                $successMessage = 'Markup updated successfully!';
    
                if ($location) {
                    $location->update($validatedData);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Markup not found!',
                    ]);
                }
            } else {
                // Create a new record
                Markup::create($validatedData);
                $successMessage = 'Markup saved successfully!';
            }


            // Markup::create($validatedData);
            // $successMessage = 'Markup saved successfully!';
            
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect_url' => url()->previous() // Redirect back URL for AJAX
            ]);

        }
    }

    public function fetchSettings($categoryId)
    {
        $settings = Setting::where('category_id', $categoryId)->get();
        
        $activeSettings = $settings->where('status', 'Active')->pluck('title','id');
        $inactiveSettings = $settings->where('status', 'Inactive')->pluck('title','id');
        
        return response()->json([
            'active' => $activeSettings,
            'inactive' => $inactiveSettings,
        ]);
    }

    public function updateSettingStatus(Request $request, $settingId)
    {
        $setting = Setting::findOrFail($settingId);
        $setting->status = $request->status;
        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Setting status updated successfully!',
        ]);

    }

    public function storeSetting(Request $request)
    {
        // Validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:setting_categories,id',
        ];

        // Custom error messages (optional)
        $messages = [
            'title.required' => 'The setting title field is required.',
            'category_id.required' => 'The category ID is required.',
            'category_id.exists' => 'The selected category ID is invalid.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        // Get validated data
        $validatedData = $validator->validated();

        // Create a new setting record
        $setting = Setting::create([
            'title' => $validatedData['title'],
            'category_id' => $validatedData['category_id'],
            'status' => "Active", // Assuming new settings are active by default
        ]);

        // Success response
        return response()->json([
            'success' => true,
            'message' => 'Setting saved successfully!',
            'setting' => $setting, // Send back the created setting for any frontend updates
        ]);
    }


}
