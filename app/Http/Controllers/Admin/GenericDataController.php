<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GenericData;
use App\Models\Location;
use App\Models\Country;
use App\Models\State;
use App\Models\Setting;
use App\Models\SettingCategory;
class GenericDataController extends BaseController
{

    public function manageData(Request $request, $formtype = null)
    {
       
        $fields = $request->route()->defaults['fields'] ?? [];
       
        if ($request->isMethod('get')) {
            
            // Handle GET request: Show form and data
            $data = GenericData::where('type', $formtype)->get();

            return view('admin.data-points.manage', [
                'formtype' => $formtype,
                'data' => $data,
                'fields' => $fields
            ]);
        } elseif ($request->isMethod('post')) {
            $fields = array_keys($request->route()->defaults['fields']);
            
            // Handle POST request: Save or update data
            $validatedData = $request->only($fields);
            // dd($validatedData, $fields);
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
                $fields = array_keys($request->route()->defaults['fields']);
                // Handle POST request: Save or update data
                $validatedData = $request->only($fields);
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

    public function locationDetail(Request $request)
    {
        // dd($request);
        if ($request->isMethod('get')) {
            // Handle GET request: Show form and data
            $data = Location::with(['country', 'state'])->get(); // Fetch all location data
            $countries = Country::all();
            return view('admin.data-points.location', [
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
            return view('admin.data-points.setting', [
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

        return response()->json(['success' => 'Setting status updated successfully']);
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
