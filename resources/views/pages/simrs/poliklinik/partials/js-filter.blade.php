<script>
    $(document).ready(function() {

        $('.filter-poli').on('change', function(e) {
            e.preventDefault(); // Mencegah form submit langsung
            console.log('changed')
            $.ajax({
                url: "{{ route('poliklinik.filter-pasien') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // Tambahkan token CSRF
                    departement_id: $('#filter_poliklinik #departement_id').val(),
                    doctor_id: $('#filter_poliklinik #doctor_id').val()
                },
                dataType: "json",
                beforeSend: function() {
                    $('#daftar-pasien-poli .col-12').html(
                    '<p>Sedang memuat...</p>'); // Tambahkan loading
                },
                success: function(response) {
                    if (response.success) {
                        $('#daftar-pasien-poli .col-12').html(response.html);
                    } else {
                        $('#daftar-pasien-poli .col-12').html(
                            '<p>Tidak ada data pasien.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi kesalahan, silakan coba lagi.");
                }
            });
        });


        if($('#filter_poliklinik #departement_id').val() != null || $('#filter_poliklinik #doctor_id').val() != null) {
            $.ajax({
                url: "{{ route('poliklinik.filter-pasien') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // Tambahkan token CSRF
                    departement_id: $('#filter_poliklinik #departement_id').val(),
                    doctor_id: $('#filter_poliklinik #doctor_id').val()
                },
                dataType: "json",
                beforeSend: function() {
                    $('#daftar-pasien-poli .col-12').html(
                    '<p>Sedang memuat...</p>'); // Tambahkan loading
                },
                success: function(response) {
                    

                    if (response.success) {
                        $('#daftar-pasien-poli .col-12').html(response.html);
                    } else {
                        $('#daftar-pasien-poli .col-12').html(
                            '<p>Tidak ada data pasien.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    alert("Terjadi kesalahan, silakan coba lagi.");
                }
            });
        } 
    });
    // Close the panel if the backdrop is clicked
</script>
