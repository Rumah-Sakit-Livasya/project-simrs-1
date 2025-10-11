{{-- Ini adalah konten untuk popup, jadi tidak perlu extends layout utama --}}
@include('pages.simrs.warehouse.purchase-request.partials.form-pr-content', [
    'action' => route('warehouse.purchase-request.pharmacy.update', $pr->id),
    'method' => 'PUT',
    'pr' => $pr,
])
