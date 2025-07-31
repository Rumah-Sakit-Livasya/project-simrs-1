{{-- File: resources/views/test-layout.blade.php --}}

@extends('inc.blank') {{-- Pastikan path ini benar --}}

@section('title', 'Test Layout Kosong')

@section('content')
    <div
        style="width: 100%; height: 100%; background-color: red; color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-family: sans-serif;">
        <h1>JIKA INI FULLSCREEN, LAYOUT BEKERJA</h1>
    </div>
@endsection
