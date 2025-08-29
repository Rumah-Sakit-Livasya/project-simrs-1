   @foreach ($banks as $bank)
       <div class="modal fade" id="delete-bank-{{ $bank->id }}" tabindex="-1" role="dialog" aria-hidden="true">
           <div class="modal-dialog modal-dialog-centered" role="document">
               <div class="modal-content">
                   <div class="modal-header bg-danger text-white">
                       <h5 class="modal-title">
                           <i class="fal fa-exclamation-triangle mr-2"></i>
                           Konfirmasi Hapus
                       </h5>
                       <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body">
                       <div class="text-center">
                           <i class="fal fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                           <h4>Apakah Anda yakin?</h4>
                           <p class="text-muted">
                               Anda akan menghapus bank <strong>"{{ $bank->nama }}"</strong><br>
                               Tindakan ini tidak dapat dibatalkan!
                           </p>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-dismiss="modal">
                           <i class="fal fa-times mr-1"></i> Batal
                       </button>
                       <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" style="display: inline;">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-danger">
                               <i class="fal fa-trash mr-1"></i> Ya, Hapus
                           </button>
                       </form>
                   </div>
               </div>
           </div>
       </div>
   @endforeach
