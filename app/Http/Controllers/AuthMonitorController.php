<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class AuthMonitorController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt (hardcoded credentials used)');

        try {
            $response = Http::retry(3, 1000)->timeout(30)->post('https://cerebro.ihc.id/api/login', [
                'email' => 'bih@ihc.id',
                'password' => 'VCJpTDN7elOJ36ItQjvgKjQ8ZElNMxRp',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Login success', ['user' => $data['user']['name'] ?? 'unknown']);

                session([
                    'token' => $data['authorization']['token'] ?? null,
                    'user_name' => $data['user']['name'] ?? '',
                    'sales_org' => $data['user']['salesOrganization'] ?? ''
                ]);

                return redirect('/home')->with('success', 'Login success!');
            }

            Log::warning('Login failed response', ['body' => $response->body()]);
            return back()->withErrors([
                'login' => 'Login Failed: ' . $response->body()
            ]);
        } catch (ConnectionException $e) {
            Log::error('Login connection timeout', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'login' => 'Server no respond (request timeout). Please try again.'
            ]);
        } catch (\Exception $e) {
            Log::error('Login exception', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'login' => 'Failed Connect server ' . $e->getMessage()
            ]);
        }
    }
}
