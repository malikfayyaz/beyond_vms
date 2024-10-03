<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function index():  View|RedirectResponse
    {
        if (Auth::check()) {
            // Get the selected role from the session
            $role = session('selected_role');
            dd($role);
            // Redirect to the appropriate dashboard based on the selected role
            return redirect()->route(redirectToDashboard($role));
        }
        return view('auth.login');
    }

    public function registration(): View
    {
        return view('auth.registration');
    }

    public function postLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Use the helper function to get active roles
            $activeRoles = getActiveRoles($user);

            if (empty($activeRoles)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account does not have an active status.',
                ])->onlyInput('email');
            }

             // If the user has multiple active roles and no role is selected
                if (count($activeRoles) > 1 && !session()->has('selected_role')) {
                    \Log::info('User has multiple roles, redirecting to role selection.');
                    return redirect()->route('type.select');
                }

                // If the user has only one active role, select it and redirect to the appropriate dashboard
                if (count($activeRoles) === 1) {
                    $role = array_key_first($activeRoles);
                    session(['selected_role' => $role]);
                    return redirect()->route(redirectToDashboard($role));
                }

            // Proceed to the dashboard if the role is already selected
            $role = session('selected_role');
            return redirect()->route(redirectToDashboard($role));
        }

        return redirect("login")->withErrors('Oops! You have entered invalid credentials');
    }

    public function postRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $user = $this->create($data);

        Auth::login($user);

        return redirect("dashboard")->withSuccess('Great! You have successfully logged in');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect("login")->withErrors('Oops! You do not have access');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logout(): JsonResponse
    {
        Session::flush();
        Auth::logout();
        return response()->json([
            'success' => true,
            'redirect_url' => route('login'),
        ]);

        // return response()->json(['message' => 'Successfully logged out'], 200);
    }



    public function showRoleSelectionForm()
    {
        $user = Auth::user();

        // Use the helper function to get active roles
        $roles = getActiveRoles($user);

        // If a role is already selected, redirect to the corresponding dashboard
        if (session()->has('selected_role')) {
            return redirect()->route(redirectToDashboard(session('selected_role')));
        }

        // If no roles are active, you might want to handle this case
        if (empty($roles)) {
            Session::flush();
            Auth::logout();
            return redirect()->route('login')->withErrors('No active roles found.');
        }

        return view('auth.select_role', compact('roles'));
    }

    public function selectRole(Request $request)
    {
        // dd($request->input('role'));
        $role = $request->input('role');
        $user = Auth::user();

        // Use the helper function to get active roles
        $activeRoles = getActiveRoles($user);

        // Check if the selected role is valid
        if (!array_key_exists($role, $activeRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid role selected',
            ]);
        }

        // Store the selected role in the session
        session(['selected_role' => $role]);

        // Return a JSON response with the redirect URL
        return response()->json([
            'success' => true,
            'redirect_url' => route(redirectToDashboard($role)),
        ]);
    }

}
