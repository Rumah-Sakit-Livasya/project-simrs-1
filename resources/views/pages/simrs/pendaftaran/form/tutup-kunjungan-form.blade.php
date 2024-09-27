    <div class="modal fade" id="tutup-kunjungan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Batal Registrasi Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form action="{{ route('tutup.kunjungan', $registration->id) }}" method="POST" autocomplete="off"
                    novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning fade show">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon width-6">
                                    <div class='icon-stack icon-stack-lg'>
                                        <i class="base base-7 icon-stack-3x opacity-100 color-warning-500 "></i> <i
                                            class="base base-14 icon-stack-2x opacity-50 color-warning-300 "></i> <i
                                            class="fal fa-info icon-stack-1x opacity-100 color-white "></i>
                                    </div>
                                </div>
                                <div class="flex-1 text-center">
                                    <span class="h3">PERHATIAN</span>
                                    <br>
                                    FORM INI AKAN MENGHAPUS DATA REGISTRASI PASIEN BERSANGKUTAN!
                                    <br>
                                    (TERMASUK DATA REKAM MEDIS, CATATAN DOKTER, DLL.)
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-xl-3" style="text-align: right">
                                    <label for="alasan_keluar">Alasan Keluar</label>
                                </div>
                                <div class="col-xl">
                                    <select class="form-control w-100" id="alasan_keluar"
                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                        name="alasan_keluar">
                                        <option value=""></option>
                                        <option value="Permintaan Pasien">Permintaan Pasien</option>
                                        <option value="Perintah Dokter">Perintah Dokter</option>
                                        <option value="Meninggal">Meninggal</option>
                                    </select>
                                    @error('alasan_keluar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-xl-3 text-right">
                                    <label for="lp_manual">No. LP Manual</label>
                                </div>
                                <div class="col-xl text-right">
                                    <input type="lp_manual" class="form-control" name="lp_manual" id="lp_manual"
                                        placeholder="Di isi jika SEP KLL" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-xl-3" style="text-align: right">
                                    <label for="proses_keluar">Proses Keluar</label>
                                </div>
                                <div class="col-xl">
                                    <select class="form-control w-100" id="proses_keluar"
                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                        name="proses_keluar">
                                        <option value=""></option>
                                        <option value="Sembuh">Sembuh</option>
                                        <option value="Dirujuk">Dirujuk</option>
                                        <option value="Meninggal">Meninggal</option>
                                        <option value="Pulang Paksa">Pulang Paksa</option>
                                        <option value="Tidak Tahu">Tidak Tahu</option>
                                        <option value="ODC">ODC</option>
                                        <option value="Masuk Rawat Inap">Masuk Rawat Inap</option>
                                    </select>
                                    @error('proses_keluar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xl-3 text-right">
                                    <label for="email">User Cancel</label>
                                </div>
                                <div class="col-xl text-right">
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{ auth()->user()->email }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-xl-3 text-right">
                                    <label for="password">Password</label>
                                </div>
                                <div class="col-xl text-right">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
