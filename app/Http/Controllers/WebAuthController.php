<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class WebAuthController extends Controller
{
    public function loginPage()
    {
        if (session()->has('token')) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function loginWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors(['login' => 'Username and password are required.'])->withInput();
        }

        if (!$token = auth('api')->attempt($request->only('username', 'password'))) {
            return back()->withErrors(['login' => 'Invalid username or password.'])->withInput();
        }

        $user = auth('api')->user();
        session(['token' => $token, 'user' => $user]);

        return redirect('/dashboard')->with('success', 'Login successful!');
    }

    public function dashboard(Request $request)
    {
        if (!$request->session()->has('token')) {
            return redirect('/login')->withErrors(['login' => 'Please log in first.']);
        }

        $user = $request->session()->get('user');
        $pendingApprovals = 0;

        if (user_has_role($user, 'PRICE_APPROVER')) {
            $pendingApprovals = \App\Models\PriceSubmission::where('status', 'PENDING')->count();
        }

        return view('dashboard', compact('user', 'pendingApprovals'));
    }

    public function settingsPage(Request $request)
    {
        if (!$request->session()->has('token')) {
            return redirect('/login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $user = $request->session()->get('user');

        if (!user_has_role($user, 'ADMIN')) {
            return redirect('/dashboard')->withErrors(['access' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        $users = User::orderBy('created_at', 'desc')->get();
        $availableRoles = config('roles.all', []);
        return view('settings', compact('user', 'users', 'availableRoles'));
    }

    public function updateUserRolesWeb(Request $request, $id)
    {
        $currentUser = session('user');

        if (!$currentUser || !user_has_role($currentUser, 'ADMIN')) {
            return redirect('/dashboard')->withErrors(['access' => 'Hanya ADMIN yang dapat mengubah role user.']);
        }

        $targetUser = User::findOrFail($id);
        $allowedRoles = config('roles.all', []);
        $validator = Validator::make($request->all(), [
            'roles' => 'required|array',
            'roles.*' => 'in:' . implode(',', $allowedRoles),
        ], [
            'roles.required' => 'Pilih minimal satu role.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $roles = array_values(array_unique($request->input('roles', [])));
        if (empty($roles)) {
            return back()->withErrors(['roles' => 'Pilih minimal satu role.'])->withInput();
        }

        $targetUser->roles = $roles;
        $targetUser->save();

        return redirect()->route('settings')->with('success', 'Role user ' . $targetUser->username . ' berhasil diperbarui.');
    }

    public function addUserWeb(Request $request)
    {
        $currentUser = session('user');

        if (!$currentUser || !user_has_role($currentUser, 'ADMIN')) {
            return redirect('/dashboard')->withErrors(['access' => 'Hanya ADMIN yang dapat menambahkan user.']);
        }

        $allowedRoles = config('roles.all', []);
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'in:' . implode(',', $allowedRoles),
        ], [
            'roles.required' => 'Pilih minimal satu role.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $roles = array_values(array_unique($request->input('roles', [])));
        if (empty($roles)) {
            return back()->withErrors(['roles' => 'Pilih minimal satu role.'])->withInput();
        }

        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->roles = $roles;
        $user->save();

        return redirect()->route('settings')->with('success', 'User added successfully!');
    }

    public function changePasswordWeb(Request $request, $id)
    {
        $currentUser = session('user');
        $targetUser = User::findOrFail($id);

        if (!user_has_role($currentUser, 'ADMIN') && ($currentUser['id'] ?? null) !== $targetUser->id) {
            return redirect()->back()->withErrors(['access' => 'Anda tidak memiliki izin untuk mengubah password ini.']);
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $targetUser->password = Hash::make($request->password);
        $targetUser->save();

        return redirect()->back()->with('success', 'Password changed successfully for ' . $targetUser->username);
    }

    public function deleteUserWeb($id)
    {
        $currentUser = session('user');

        if (!user_has_role($currentUser, 'ADMIN')) {
            return redirect()->back()->withErrors(['access' => 'Hanya ADMIN yang dapat menghapus user.']);
        }

        $user = User::findOrFail($id);

        if ($user->id === ($currentUser['id'] ?? $currentUser->id ?? null)) {
            return redirect()->back()->withErrors(['access' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();
        return redirect()->back()->with('success', 'User ' . $user->username . ' deleted successfully!');
    }

    public function logoutWeb(Request $request)
    {
        $token = $request->session()->get('token');

        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
            } catch (\Exception $e) {
            }
        }

        $request->session()->flush();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
