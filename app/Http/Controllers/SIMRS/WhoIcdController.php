<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhoIcdController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $tokenUrl = 'https://icdaccessmanagement.who.int/connect/token';
    private $baseUrl = 'https://id.who.int/icd';

    public function __construct()
    {
        $this->clientId = config('app.who_icd_client_id', env('WHO_ICD_CLIENT_ID'));
        $this->clientSecret = config('app.who_icd_client_secret', env('WHO_ICD_CLIENT_SECRET'));
    }

    private function getAccessToken()
    {
        return Cache::remember('who_icd_api_token', 3300, function () {
            $response = Http::asForm()->post($this->tokenUrl, [
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope'         => 'icdapi_access',
            ]);

            if ($response->failed()) {
                Log::error('WHO ICD API Auth Failed: ' . $response->body());
                return null;
            }

            return $response->json()['access_token'];
        });
    }


    public function search(Request $request)
    {
        $searchTerm = $request->query('q');
        if (empty($searchTerm)) {
            return response()->json([]);
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return response()->json(['error' => 'Could not authenticate with API'], 500);
        }

        // =========================================================================
        // PERBAIKAN FINAL: Menggunakan URL pencarian ICD-11 yang valid
        // =========================================================================
        $searchUrl = "{$this->baseUrl}/release/11/2024-01/mms/search";

        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'API-Version'     => 'v2',
                    'Accept-Language' => 'en',
                    'Accept'          => 'application/json',
                ])
                ->get($searchUrl, ['q' => $searchTerm]);

            if ($response->failed()) {
                Log::error('WHO ICD API Search Failed: ' . $response->body());
                return response()->json([]);
            }

            $results = $response->json();
            $formattedResults = [];

            if (!empty($results['destinationEntities'])) {
                foreach ($results['destinationEntities'] as $entity) {
                    // Cek apakah 'theCode' ada sebelum digunakan
                    $code = !empty($entity['theCode']) ? $entity['theCode'] : 'N/A';
                    $formattedResults[] = [
                        'id'   => $entity['id'],
                        'text' => $code . ' - ' . $entity['title']
                    ];
                }
            }

            return response()->json($formattedResults);
        } catch (\Exception $e) {
            Log::error('Exception during WHO ICD API call: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
