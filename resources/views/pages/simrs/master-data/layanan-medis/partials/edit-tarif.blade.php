<!-- Modal -->
<div class="modal fade" id="modal-edit-tarif" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="POST" id="update-tarif-form">
                @csrf
                @method('PATCH')
                <input type="hidden" id="tindakan-medis-id" name="tindakan_medis_id">
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">
                        Edit Tarif Tindakan Medis - <span id="nama-tindakan" class="text-primary"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>

                <div class="modal-body py-2">
                    <div id="loader-wrapper">
                        <div id="loader"></div>
                        <div class="loader-section section-left"></div>
                        <div class="loader-section section-right"></div>
                    </div>

                    <!-- Dropdown untuk Group Penjamin -->
                    <div class="form-group">
                        <label for="group-penjamin">Group Penjamin</label>
                        <select id="group-penjamin" class="form-control select2" name="group_penjamin">
                            <option value="" selected>Pilih Group Penjamin</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <table width="100%" id="datatable">
                        <tr>
                            <th style="white-space: nowrap">Nama Kelas</th>
                            <th>Share dr</th>
                            <th>Share RS</th>
                            <th>Prasarana</th>
                            <th>BHP</th>
                            <th>Total</th>
                        </tr>
                        <tbody id="tarif-inputs"></tbody>
                    </table>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="bSave" class="btn mx-1 btn-primary text-white" title="Update">
                        <span class="fal fa-save mr-1"></span>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
