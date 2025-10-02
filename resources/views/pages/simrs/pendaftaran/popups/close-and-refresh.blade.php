<!DOCTYPE html>
<html>

<head>
    <title>Processing...</title>
    <script>
        // Fungsi ini akan dieksekusi setelah halaman dimuat
        window.onload = function() {
            try {
                // Mereload halaman parent (halaman detail bilingan)
                window.opener.location.reload(true);
            } catch (e) {
                console.error("Could not reload opener window:", e);
            } finally {
                // Menutup jendela pop-up saat ini
                window.close();
            }
        };
    </script>
</head>

<body>
    <p>Perubahan berhasil disimpan. Jendela ini akan tertutup secara otomatis...</p>
</body>

</html>
