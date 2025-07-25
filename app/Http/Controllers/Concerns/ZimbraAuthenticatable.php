<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Log;

trait ZimbraAuthenticatable
{
    /**
     * Melakukan otentikasi ke server ZIMBRA.
     * 
     * @param string $email
     * @param string $password
     * @return bool
     */
    private function zimbraLogin($email, $password): bool
    {
        $data = [
            "Header" => [
                "context" => [
                    "_jsns" => "urn:zimbra",
                    "userAgent" => ["name" => "Laravel-App", "version" => "1.0"],
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
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpcode == 200 && strpos($result, 'AUTH_FAILED') === false) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Zimbra login cURL failed: ' . $e->getMessage());
            return false;
        }
    }
}
