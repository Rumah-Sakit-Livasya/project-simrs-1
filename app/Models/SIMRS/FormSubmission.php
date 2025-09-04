<?php

namespace App\Models\SIMRS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'form_submissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'form_template_id',
        'form_values',
        'is_final',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'form_values' => 'array', // Ini akan otomatis mengubah JSON dari DB menjadi array PHP, dan sebaliknya
        'is_final' => 'boolean',
    ];

    // Definisikan relasi jika diperlukan
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function template()
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
