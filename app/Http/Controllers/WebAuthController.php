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
            return back()->withErrors(['login' => 'Username dan password wajib diisi.'])->withInput();
        }

        if (!$token = auth('api')->attempt($request->only('username', 'password'))) {
            return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
        }

        $user = auth('api')->user();
        session(['token' => $token, 'user' => $user]);

        return redirect('/dashboard')->with('success', 'Login berhasil!');
    }

    public function dashboard(Request $request)
    {
        if (!$request->session()->has('token')) {
            return redirect('/login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $user = $request->session()->get('user');
        $pendingApprovals = 0;

        if (isset($user['role']) && $user['role'] == 'PRICE_APPROVER') {
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

        if ($user['role'] !== 'ADMIN') {
            return redirect('/dashboard')->withErrors(['access' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return view('settings', compact('user', 'users'));
    }

    public function addUserWeb(Request $request)
    {
        $currentUser = session('user');

        if (!$currentUser || $currentUser['role'] !== 'ADMIN') {
            return redirect('/dashboard')->withErrors(['access' => 'Hanya ADMIN yang dapat menambahkan user.']);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:ADMIN,PRICE_STRATEGY,PRICE_ENTRY,PRICE_APPROVER',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('settings')->with('success', 'User berhasil ditambahkan!');
    }

    public function changePasswordWeb(Request $request, $id)
    {
        $currentUser = session('user');
        $targetUser = User::findOrFail($id);

        if ($currentUser['role'] !== 'ADMIN' && $currentUser['id'] !== $targetUser->id) {
            return redirect()->back()->withErrors(['access' => 'Anda tidak memiliki izin untuk mengubah password ini.']);
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $targetUser->password = Hash::make($request->password);
        $targetUser->save();

        return redirect()->back()->with('success', 'Password berhasil diubah untuk ' . $targetUser->username);
    }

    public function deleteUserWeb($id)
    {
        $currentUser = session('user');

        if ($currentUser['role'] !== 'ADMIN') {
            return redirect()->back()->withErrors(['access' => 'Hanya ADMIN yang dapat menghapus user.']);
        }

        $user = User::findOrFail($id);

        if ($user->id === $currentUser['id']) {
            return redirect()->back()->withErrors(['access' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();
        return redirect()->back()->with('success', 'User ' . $user->username . ' berhasil dihapus!');
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
        return redirect('/login')->with('success', 'Berhasil logout!');
    }
}
