<?php
// app/Http/Controllers/LinkController.php
namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index()
    {
        $query = Link::query();

        if (Auth::user()->hasRole('super admin')) {
            // Super admin melihat semua links
            $links = $query->orderBy('created_at', 'desc')->get();
        } else {
            // User biasa hanya melihat links miliknya
            $links = $query->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('pages.links.index', compact('links'));
    }

    public function shorten(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $originalUrl = $request->input('url');
        $shortCode = Str::random(10);

        $link = Link::create([
            'original_url' => $originalUrl,
            'short_code' => $shortCode
        ]);

        // Simpan short URL di session untuk ditampilkan
        $request->session()->flash('short_url', url('/links') . '/' . $shortCode);
        $request->session()->flash('original_url', $originalUrl);

        // Redirect ke route /links
        return redirect()->route('links.index');
    }

    // // app/Http/Controllers/LinkController.php
    // public function showAnalytics($id)
    // {
    //     $link = Link::findOrFail($id);

    //     // Jika Anda ingin menambahkan data analytics lebih detail
    //     $clicksByDay = DB::table('link_clicks') // Asumsi Anda memiliki tabel tracking klik
    //         ->where('link_id', $id)
    //         ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as clicks'))
    //         ->groupBy('date')
    //         ->orderBy('date', 'asc')
    //         ->get();

    //     return view('pages.links.analytics', compact('link', 'clicksByDay'));
    // }

    public function redirect($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        // Update hit counter
        $link->increment('clicks');

        return redirect($link->original_url);
    }

    public function destroy($id)
    {
        $link = Link::find($id);

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Link tidak ditemukan'
            ], 404);
        }

        $link->delete();

        return response()->json([
            'success' => true,
            'message' => 'Link berhasil dihapus'
        ]);
    }
}
