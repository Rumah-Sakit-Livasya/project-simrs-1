<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt Zimbra login if local authentication fails
        if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
            // Zimbra authentication successful
            $user = User::where('email', $credentials['email'])->where('is_active', 1)->first();

            if ($user == null) {
                throw ValidationException::withMessages([
                    'email' => 'User tidak ditemukan!',
                ]);
            }
            Auth::login($user, true); // Log the user in
            $request->session()->regenerate(); // Regenerate session
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            // Both local and Zimbra authentication failed
            throw ValidationException::withMessages([
                'email' => 'Email atau Password salah!',
            ]);
        }
    }

    private function zimbraLogin($email, $password)
    {
        $data = [
            "Header" => [
                "context" => [
                    "_jsns" => "urn:zimbra",
                    "userAgent" => ["name" => "curl", "version" => "8.8.15"],
                ],
            ],
            "Body" => [
                "AuthRequest" => [
                    "_jsns" => "urn:zimbraAccount",
                    "account" => ["_content" => $email, "by" => "name"],
                    "password" => $password,
                ],
            ],
        ];

        try {
            $encodedData = json_encode($data);

            $url = 'https://webmail.livasya.com/service/soap';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json'
            ));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);

            $mark = 'AUTH_FAILED';

            if (strpos($result, $mark) !== false) {
                return false; // Autentikasi gagal
            } else {
                // Autentikasi berhasil
                // Anda mungkin ingin melakukan sesuatu di sini, seperti memproses respons
                // atau mengembalikan informasi tambahan
                return true;
            }
        } catch (\Exception $e) {
            // Tangani kesalahan saat menjalankan permintaan cURL
            return false;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
