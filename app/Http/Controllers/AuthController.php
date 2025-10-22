<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            $response = Http::retry(3, 1000)->timeout(30)->post('https://cerebro.ihc.id/api/login', [
                'email' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                session([
                    'token' => $data['authorization']['token'] ?? null,
                    'user_name' => $data['user']['name'] ?? '',
                    'sales_org' => $data['user']['salesOrganization'] ?? ''
                ]);

                return redirect('/home')->with('success', 'Login success!');
            }

            return back()->withErrors([
                'login' => 'Login Failed: ' . $response->body()
            ]);
        } catch (ConnectionException $e) {
            return back()->withErrors([
                'login' => 'Server no respond (request timeout). Please try again.'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'login' => 'Failed Connect server ' . $e->getMessage()
            ]);
        }
    }

    public function showManualLogin()
    {
        return view('login_token');
    }

    public function manualLogin(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        session([
            'token' => trim($request->token),
            'user_name' => $request->user_name ?? 'Manual User',
            'sales_org' => $request->sales_org ?? 'Manual Org'
        ]);

        return redirect('/home')->with('success', 'Login manual berhasil!');
    }
}
