<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Tanda Tangan untuk <span id="employeeName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="signatureForm">
                    @csrf
                    <input type="hidden" name="employee_id" id="employeeId">

                    <div class="text-center mb-3">
                        <h6>Tanda Tangan Saat Ini:</h6>
                        <img id="currentSignature" src="" alt="Belum Ada Tanda Tangan"
                            style="max-width: 200px; height: auto; border: 1px solid #ccc; display: none;">
                        <p id="noSignatureText">Belum ada tanda tangan.</p>
                    </div>

                    <div class="wrapper"
                        style="border: 1px dashed #ccc; border-radius: 5px; background-color: #f8f9fa;">
                        <canvas id="signature-pad" class="signature-pad" width=450 height=200></canvas>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id="clearSignature">Hapus</button>
                <button type="button" class="btn btn-primary" id="saveSignature">Simpan</button>
            </div>
        </div>
    </div>
</div>
