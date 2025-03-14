<?php

namespace App\Http\Controllers\SIMRS\Pengkajian;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Pengkajian\FormKategori;
use App\Models\SIMRS\Pengkajian\FormTemplate;
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    public function index() {
        $form = FormTemplate::latest()->get();
        return view('pages.simrs.master-data.form-builder.index', compact('form'));
    }
    
    public function create() {
        $kategori = FormKategori::latest()->get();
        return view('pages.simrs.master-data.form-builder.tambah', compact('kategori'));
    }
}
