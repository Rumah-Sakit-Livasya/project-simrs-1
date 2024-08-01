<div class="modal fade font-weight-bold p-0" id="picture-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form" enctype="multipart/form-data">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Swafoto untuk Absen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="video-container">
                                    <video id="video" class="w-100" autoplay playsinline></video>
                                </div>
                                <canvas id="canvas" width="640" height="480" style="display: none;"></canvas>
                                <div class="text-center mt-2 mb-4">
                                    <button type="button" class="btn btn-primary" id="upload">
                                        <span class="spinner-border mr-1 spinner-text spinner-border-sm d-none"
                                            role="status" aria-hidden="true"></span>Absen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
