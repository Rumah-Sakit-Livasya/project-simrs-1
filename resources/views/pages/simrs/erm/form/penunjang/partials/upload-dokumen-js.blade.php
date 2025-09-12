<script>
    $(document).ready(function() {
        if (typeof Dropzone !== 'undefined') {
            Dropzone.autoDiscover = false;
        }

        // Fungsi ini dipanggil dari window popup untuk mengupdate halaman utama
        window.updateSignature = function(targetInputId, targetPreviewId, dataURL) {
            // Cari elemen di halaman utama dan isi nilainya
            const inputField = document.getElementById(targetInputId);
            const previewImage = document.getElementById(targetPreviewId);

            if (inputField) {
                inputField.value = dataURL;
            }
            if (previewImage) {
                previewImage.src = dataURL;
                previewImage.style.display = 'block';
            }
        };

        // Fungsi ini dipanggil oleh tombol "Tanda Tangan" untuk membuka popup
        window.openSignaturePopup = function(targetInputId, targetPreviewId) {
            const windowWidth = screen.availWidth;
            const windowHeight = screen.availHeight;
            const left = 0;
            const top = 0;

            // Bangun URL dengan query string untuk memberitahu popup elemen mana yang harus diupdate
            const url =
                `{{ route('signature.pad') }}?targetInput=${targetInputId}&targetPreview=${targetPreviewId}`;

            // Buka popup window
            window.open(
                url,
                'SignatureWindow',
                `width=${windowWidth},height=${windowHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
            );
        };

        // Inisialisasi Select2
        $('.select2').select2();

        // 1. Inisialisasi Datatables (TETAP SAMA)
        var table = $('#documents-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('erm.dokumen.data', ['registration' => $registration->id]) }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'category_name',
                    name: 'category.name'
                },
                {
                    data: 'original_filename',
                    name: 'original_filename'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'user_name',
                    name: 'user.name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ]
        });


        // 2. LOGIKA UNGGAH PURE AJAX (BARU)
        $("#btn-upload-document").on("click", function(e) {
            e.preventDefault();

            const btn = $(this);
            const form = $('#form-upload-document');
            const fileInput = document.getElementById('file_upload');

            // Validasi Sederhana
            if (!fileInput.files.length) {
                Swal.fire('Peringatan', 'Anda belum memilih file untuk diunggah.', 'warning');
                return;
            }
            if ($('#document_category_id').val() === "") {
                Swal.fire('Peringatan', 'Tipe Dokumen harus dipilih.', 'warning');
                return;
            }

            // Siapkan FormData
            const formData = new FormData(form[0]);

            // Karena nama input file di Blade adalah file_upload, kita harus mengirimnya ke Laravel
            // dengan nama 'file' (sesuai yang divalidasi di controller)
            formData.append('file', fileInput.files[0]);

            // Disable tombol saat proses
            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah...'
                );

            $.ajax({
                url: "{{ route('erm.dokumen.store') }}",
                type: 'POST',
                data: formData,
                // Kunci sukses untuk upload file
                processData: false,
                contentType: false,

                success: function(response) {
                    Swal.fire('Berhasil!', response.success, 'success');
                    table.ajax.reload(); // Muat ulang Datatables
                    form[0].reset(); // Reset form
                    $('#document_category_id').val('').trigger('change'); // Reset Select2
                },
                error: function(xhr) {
                    let errorMessage = "Gagal mengunggah file. Silakan coba lagi.";
                    // Menangani error validasi dari Laravel (Status 422)
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                            '<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal!', errorMessage, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html(
                        '<i class="fas fa-paper-plane"></i> Unggah Sekarang');
                }
            });
        });

        // 3. LOGIKA HAPUS DOKUMEN (TETAP SAMA)
        $(document).on('click', '.btn-delete-document', function() {
            // ... (kode hapus tetap sama seperti di versi Dropzone sebelumnya) ...
            var docId = $(this).data('id');
            var url = "{{ url('api/simrs/erm/dokumen/destroy') }}/" + docId;

            Swal.fire({
                title: 'Anda Yakin?',
                text: "Dokumen ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Dihapus!', response.success, 'success');
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!',
                                'Terjadi kesalahan saat menghapus file.',
                                'error');
                        }
                    });
                }
            });
        });

    });
</script>
