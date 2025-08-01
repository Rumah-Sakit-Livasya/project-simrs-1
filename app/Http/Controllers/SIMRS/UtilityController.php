<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    /**
     * Menampilkan halaman popup untuk tanda tangan.
     */
    public function showSignaturePad(Request $request)
    {
        // Ambil ID target dari query parameter URL
        $inputTarget = $request->query('inputTarget');
        $previewTarget = $request->query('previewTarget');

        // Pastikan target ada untuk keamanan
        if (!$inputTarget || !$previewTarget) {
            return response('Target tidak valid.', 400);
        }

        return view('pages.simrs.utility.signature_pad', compact('inputTarget', 'previewTarget'));
    }
}
