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
                    // Bisa ditambahkan loader atau efek loading di sini
                },
                success: function(response) {
                    console.log(response);
                    $('#hasil_filter').html(response.html);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    alert("Terjadi kesalahan, silakan coba lagi.");
                }
            });
        });


    });
    // Close the panel if the backdrop is clicked
</script>
