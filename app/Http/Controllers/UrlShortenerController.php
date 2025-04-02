<?php
// app/Http/Controllers/UrlShortenerController.php
namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrlShortenerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
            'custom_code' => 'nullable|alpha_dash|max:20'
        ]);

        $shortCode = $request->custom_code ?? Str::random(6);

        // Check if code exists and append unique identifier if needed
        if ($request->custom_code && Link::where('short_code', $shortCode)->exists()) {
            $shortCode = $this->generateUniqueCode($shortCode);
        }

        $data = [
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
            'user_id' => auth()->id()
        ];

        $link = Link::create($data);

        return back()->with('success', 'Link berhasil dibuat!')
            ->with('short_url', url('/links/' . $link->short_code));
    }

    /**
     * Generate unique code by appending random string
     */
    protected function generateUniqueCode($baseCode)
    {
        $maxAttempts = 5;
        $attempt = 0;

        do {
            $uniqueSuffix = '-' . Str::random(4);
            $newCode = substr($baseCode, 0, 15) . $uniqueSuffix; // Ensure total length <= 20
            $attempt++;
        } while (Link::where('short_code', $newCode)->exists() && $attempt < $maxAttempts);

        return $attempt < $maxAttempts ? $newCode : $baseCode . '-' . Str::random(4) . time();
    }

    public function destroy($id)
    {
        $link = auth()->user()->links()->findOrFail($id);
        $link->delete();

        return response()->json(['success' => true]);
    }
}
