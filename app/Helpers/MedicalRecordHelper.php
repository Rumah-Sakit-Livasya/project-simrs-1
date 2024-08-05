<?php

namespace App\Helpers;

use App\Models\SIMRS\Patient;
use Illuminate\Support\Facades\Cache;

class MedicalRecordHelper
{
    public static function generateMedicalRecordNumber()
    {
        // Use cache to store the last generated medical record number
        $cacheKey = 'last_medical_record_number';
        $latestRecordNumber = Cache::get($cacheKey);

        if (!$latestRecordNumber) {
            $latestRecord = Patient::orderBy('medical_record_number', 'desc')->first();
            if ($latestRecord) {
                $latestRecordNumber = $latestRecord->medical_record_number;
            } else {
                // If no record exists, start with the initial number
                $latestRecordNumber = '00-00-00';
            }
        }

        // Split the latest medical record number into parts
        $parts = explode('-', $latestRecordNumber);
        $num1 = (int)$parts[0];
        $num2 = (int)$parts[1];
        $num3 = (int)$parts[2];

        // Increment the parts appropriately
        if ($num3 < 99) {
            $num3++;
        } else {
            $num3 = 1;
            if ($num2 < 99) {
                $num2++;
            } else {
                $num2 = 0;
                $num1++;
            }
        }

        // Format the new medical record number
        $newRecordNumber = str_pad($num1, 2, '0', STR_PAD_LEFT) . '-' .
            str_pad($num2, 2, '0', STR_PAD_LEFT) . '-' .
            str_pad($num3, 2, '0', STR_PAD_LEFT);

        // Update the cache with the new medical record number
        Cache::put($cacheKey, $newRecordNumber);

        return $newRecordNumber;
    }
}
