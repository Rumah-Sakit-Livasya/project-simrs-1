<div class="modal fade" id="editModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('warehouse.master-data.supplier.update', ['id' => $supplier->id ]) }}" method="post">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ $supplier->id }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Supplier</h1>
                </div>
                <div class="modal-body">

                    <div class="row justify-content-center">
                        <div class="col-xl-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="nama">Nama Supplier</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->nama }}" class="form-control" id="nama" name="nama">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="nama">Kategori</label>
                                    </div>
                                    <div class="col-xl">
                                        <select required name="kategori" id="kategori" class="form-control">
                                            <option value="" selected hidden disabled>Pilih Kategori</option>
                                            <option value="FARMASI" {{ $supplier->kategori == 'FARMASI' ? 'selected' : '' }}>Farmasi</option>
                                            <option value="UMUM" {{ $supplier->kategori == 'UMUM' ? 'selected' : '' }}>Umum</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="alamat">Alamat</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->alamat }}" class="form-control" id="alamat" name="alamat">
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="phone">Phone</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->phone }}" class="form-control" id="phone" name="phone">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="fax">Fax</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->fax }}" class="form-control" id="fax" name="fax">
                                        @error('fax')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="email">Email</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->email }}" class="form-control" id="email" name="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="contact_person">Contact Person (CP)</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->contact_person }}" class="form-control" id="contact_person" name="contact_person">
                                        @error('contact_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="contact_person_phone">CP Phone</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->contact_person_phone }}" class="form-control" id="contact_person_phone" name="contact_person_phone">
                                        @error('contact_person_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="contact_person_email">CP Email</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->contact_person_email }}" class="form-control" id="contact_person_email" name="contact_person_email">
                                        @error('contact_person_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="no_rek">No Rek</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->no_rek }}" class="form-control" id="no_rek" name="no_rek">
                                        @error('no_rek')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="bank">Bank</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->bank }}" class="form-control" id="bank" name="bank">
                                        @error('bank')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="top">TOP</label>
                                    </div>
                                    <div class="col-xl">
                                        <select name="top" id="top" class="form-control">
                                            <option value="" selected hidden disabled>Pilih TOP</option>
                                            <option value="COD" {{ $supplier->top == 'COD' ? 'selected' : '' }}>COD</option>
                                            <option value="7HARI" {{ $supplier->top == '7HARI' ? 'selected' : '' }}>7 HARI</option>
                                            <option value="14HARI" {{ $supplier->top == '14HARI' ? 'selected' : '' }}>14 HARI</option>
                                            <option value="21HARI" {{ $supplier->top == '21HARI' ? 'selected' : '' }}>21 HARI</option>
                                            <option value="24HARI" {{ $supplier->top == '24HARI' ? 'selected' : '' }}>24 HARI</option>
                                            <option value="30HARI" {{ $supplier->top == '30HARI' ? 'selected' : '' }}>30 HARI</option>
                                            <option value="37HARI" {{ $supplier->top == '37HARI' ? 'selected' : '' }}>37 HARI</option>
                                            <option value="40HARI" {{ $supplier->top == '40HARI' ? 'selected' : '' }}>40 HARI</option>
                                            <option value="45HARI" {{ $supplier->top == '45HARI' ? 'selected' : '' }}>45 HARI</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="tipe_top">Tipe TOP</label>
                                    </div>
                                    <div class="col-xl">
                                        <select name="tipe_top" id="tipe_top" class="form-control">
                                            <option value="" selected hidden disabled>Pilih Tipe TOP</option>
                                            <option value="SETELAH_TUKAR_FAKTUR" {{ $supplier->tipe_top == 'SETELAH_TUKAR_FAKTUR' ? 'selected' : '' }}>Setelah Tukar Faktur</option>
                                            <option value="SETELAH_TERIMA_BARANG" {{ $supplier->tipe_top == 'SETELAH_TERIMA_BARANG' ? 'selected' : '' }}>Setelah Terima Barang</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="ppn">PPN</label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ $supplier->ppn }}" class="form-control" id="ppn" name="ppn" onkeyup="formatInputToNumber(this)">
                                        @error('ppn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 text-end">
                                        <label class="form-label" for="aktif">Status Aktif</label>
                                    </div>
                                    <div class="col-xl">
                                        <select name="aktif" id="status-aktif" class="form-control">
                                            <option value="1" {{ $supplier->aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ $supplier->aktif == 0 ? 'selected' : '' }}>Non Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-plus mr-1"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
