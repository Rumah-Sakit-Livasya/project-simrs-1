<?php

namespace App\Helpers;

use App\Models\SIMRS\Pelayanan\Triage;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\InpatientInitialExamination;

class ErmHelper
{
    /**
     * Maps rawData from various models to a standard object.
     *
     * @param mixed $rawData Model Triage, PengkajianNurseRajal, atau InpatientInitialExamination.
     * @return object|null
     */
    public static function mapRawDataToStandardObject($rawData): ?object
    {
        if (! $rawData) {
            return null;
        }

        // Inisialisasi dengan semua kemungkinan field bernilai null
        $data = (object) [
            // General
            'created_at' => null,
            'doctor_name' => null,
            'pr' => null,
            'rr' => null,
            'bp' => null,
            'temperatur' => null,
            'body_height' => null,
            'body_weight' => null,
            'sp02' => null,
            'skor_nyeri' => null,
            'keluhan_utama' => null,
            'diagnosa_keperawatan' => null,
            'allergy_medicine' => null,
            'riwayat_penyakit_sekarang' => null,
            'riwayat_penyakit_dahulu' => null,
            'riwayat_penyakit_keluarga' => null,
            'diagnosis' => null,
            'registration_notes' => null,
            'therapies' => null,
            'intervensi_keperawatan' => null,
            // Rawat Inap Specific
            'keadaan_umum' => null,
            'skor_ews' => null,
            'skor_resiko_jatuh' => null,
            'rencana_tindak_lanjut' => null,
        ];

        if ($rawData instanceof Triage) {
            $data->created_at = $rawData->created_at;
            $data->doctor_name = $rawData->doctor?->employee?->fullname;
            $data->pr = $rawData->pr;
            $data->rr = $rawData->rr;
            $data->bp = $rawData->bp;
            $data->temperatur = $rawData->temperatur;
            $data->body_height = $rawData->body_height;
            $data->body_weight = $rawData->body_weight;
            $data->sp02 = $rawData->sp02;
            // Add assignment for therapies or other fields if exist, if needed
        } elseif ($rawData instanceof PengkajianNurseRajal) {
            $data->created_at = $rawData->created_at;
            $data->doctor_name = $rawData->doctor?->employee?->fullname;
            $data->allergy_medicine = $rawData->allergy_medicine;
            $data->pr = $rawData->pr;
            $data->rr = $rawData->rr;
            $data->bp = $rawData->bp;
            $data->temperatur = $rawData->temperatur;
            $data->body_height = $rawData->body_height;
            $data->body_weight = $rawData->body_weight;
            $data->sp02 = $rawData->sp02;
            $data->skor_nyeri = $rawData->skor_nyeri;
            $data->keluhan_utama = $rawData->keluhan_utama;
            $data->diagnosa_keperawatan = $rawData->diagnosa_keperawatan;
            $data->riwayat_penyakit_sekarang = $rawData->riwayat_penyakit_sekarang;
            $data->riwayat_penyakit_dahulu = $rawData->riwayat_penyakit_dahulu;
            $data->riwayat_penyakit_keluarga = $rawData->riwayat_penyakit_keluarga;
            // Contoh jika ada kolom diagnosis, registration_notes, etc
            if (property_exists($rawData, 'diagnosis')) {
                $data->diagnosis = $rawData->diagnosis;
            }
            if (property_exists($rawData, 'registration_notes')) {
                $data->registration_notes = $rawData->registration_notes;
            }
            if (property_exists($rawData, 'therapies')) {
                $data->therapies = $rawData->therapies;
            }
            if (property_exists($rawData, 'intervensi_keperawatan')) {
                $data->intervensi_keperawatan = $rawData->intervensi_keperawatan;
            }
        } elseif ($rawData instanceof InpatientInitialExamination) {
            $data->created_at = $rawData->created_at;
            $data->doctor_name = $rawData->doctor?->employee?->fullname;

            // Vital Signs
            $data->pr = $rawData->vital_sign_pr ?? null;
            $data->rr = $rawData->vital_sign_rr ?? null;
            $data->bp = $rawData->vital_sign_bp ?? null;
            $data->temperatur = $rawData->vital_sign_temperature ?? null;
            $data->sp02 = $rawData->vital_sign_spo2 ?? null;

            // Anthropometry
            $data->body_height = $rawData->anthropometry_height ?? null;
            $data->body_weight = $rawData->anthropometry_weight ?? null;

            // Riwayat
            $data->riwayat_penyakit_sekarang = $rawData->history_of_present_illness ?? null;
            $data->riwayat_penyakit_dahulu = $rawData->past_medical_history ?? null;
            $data->riwayat_penyakit_keluarga = $rawData->family_medical_history ?? null;
            $data->keluhan_utama = $rawData->main_complaint ?? null;

            // Data spesifik Rawat Inap
            $data->keadaan_umum = $rawData->general_condition ?? null;
            $data->skor_ews = $rawData->ews_score ?? null;
            $data->skor_nyeri = $rawData->pain_score ?? null;
            $data->skor_resiko_jatuh = $rawData->fall_risk_score ?? null;
            $data->diagnosis = $rawData->working_diagnosis ?? null;
            $data->rencana_tindak_lanjut = $rawData->follow_up_plan ?? null;
            if (property_exists($rawData, 'therapies')) {
                $data->therapies = $rawData->therapies;
            }
            if (property_exists($rawData, 'intervensi_keperawatan')) {
                $data->intervensi_keperawatan = $rawData->intervensi_keperawatan;
            }
        }

        return $data;
    }
}
