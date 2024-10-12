<form id="signature-form" method="post" action="/save-signature" style="display: none;">
    @csrf
    <input type="hidden" name="signature_image" id="signature_image">
</form>
<!-- Modal for Signature Pad -->
<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Signature Pad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas id="canvas" width="600" height="300"></canvas>
                <div class="mt-2 text-center">
                    <button class="btn btn-primary" onclick="undo()">Undo</button>
                    <button class="btn btn-secondary" onclick="clearCanvas()">Clear</button>
                    <button class="btn btn-success" onclick="saveSignature()">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
