<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchUserController extends Controller
{
    public function impersonate(Request $request)
    {
        $userId = $request->input('user_id');
        $originalUserId = intval($request->input('original_user'));
        session(['original_user_id' => $originalUserId]); // Simpan ID pengguna asli ke sesi

        Auth::loginUsingId($userId); // Login sebagai pengguna yang diimpersonasi

        return redirect()->back()->with('success', 'Anda sekarang mengimersonasi pengguna.');
    }


    // Logika di UserController
    public function switchBack(Request $request)
    {
        // Pastikan ada original_user_id di sesi
        if (session()->has('original_user_id')) {
            $originalUserId = session('original_user_id');

            // Login kembali menggunakan ID pengguna asli
            Auth::loginUsingId($originalUserId);

            // Hapus ID pengguna asli dari sesi
            session()->forget('original_user_id');

            // Arahkan kembali ke halaman sebelumnya
            return redirect()->back()->with('success', 'Anda telah kembali ke pengguna asli.');
        }

        // Jika tidak ada pengguna asli, arahkan kembali dengan pesan error
        return redirect()->back()->with('error', 'Tidak ada pengguna asli untuk kembali.');
    }
}
