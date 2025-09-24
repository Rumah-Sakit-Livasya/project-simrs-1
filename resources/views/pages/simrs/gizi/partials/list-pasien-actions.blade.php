<div class="d-flex demo">
    <button class="btn btn-secondary btn-sm btn-icon rounded-circle waves-effect waves-themed action-btn"
        data-action="pilih-diet" data-id="{{ $registration->id }}" title="Pilih Kategori Diet">
        <i class="fal fa-utensil-spoon"></i>
    </button>
    <button class="btn btn-success btn-sm btn-icon rounded-circle waves-effect waves-themed ml-1 action-btn"
        data-action="order-pasien" data-id="{{ $registration->id }}" title="Order Makanan Pasien">
        <i class="fal fa-user-md"></i>
    </button>
    <button class="btn btn-info btn-sm btn-icon rounded-circle waves-effect waves-themed ml-1 action-btn"
        data-action="order-keluarga" data-id="{{ $registration->id }}" title="Order Makanan Keluarga Pasien">
        <i class="fal fa-users"></i>
    </button>
</div>
