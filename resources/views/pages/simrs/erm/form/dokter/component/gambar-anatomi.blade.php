 <h4 class="text-primary mt-4 font-weight-bold">V. LOKALISASI (GAMBAR ANATOMI)</h4>
 <div class="row">
     <div class="col-md-6">
         <label>Tubuh</label>
         <div id="img-tubuh" style="min-height: 450px; border: 1px solid #ddd;"></div>
         <input type="hidden" name="gambar_anatomi[tubuh]" id="myimage-tubuh" value="{{ $data['gambar_anatomi']['tubuh'] ?? '' }}">
     </div>
     <div class="col-md-6">
         <label>Kepala</label>
         <div id="img-kepala" style="min-height: 450px; border: 1px solid #ddd;"></div>
         <input type="hidden" name="gambar_anatomi[kepala]" id="myimage-kepala" value="{{ $data['gambar_anatomi']['kepala'] ?? '' }}">
     </div>
 </div>
