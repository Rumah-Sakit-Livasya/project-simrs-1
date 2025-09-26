$(document).ready(function () {
    // =================================================================
    // 1. INISIALISASI
    // =================================================================

    // Inisialisasi plugin Select2
    $("#kategori_id, #waktu_makan").select2({
        placeholder: "Pilih...",
        allowClear: true,
    });

    $(".select2-food-search").select2({
        placeholder: "Ketik nama makanan...",
        allowClear: true,
    });

    // =================================================================
    // 2. FUNGSI UTAMA (LOGIKA INTI)
    // =================================================================

    /**
     * Menghitung ulang total harga pesanan berdasarkan item di tabel.
     */
    function calculateTotal() {
        let total = 0;
        $("#table-food tr").each(function () {
            const qty = parseInt($(this).find(".qty-input").val()) || 0;
            const harga = parseInt($(this).data("harga")) || 0;
            const subtotal = qty * harga;

            // Update tampilan subtotal per baris
            $(this)
                .find(".subtotal-display")
                .text(new Intl.NumberFormat("id-ID").format(subtotal));

            total += subtotal;
        });

        // Update tampilan total keseluruhan
        $("#harga-display").text(
            "Rp " + new Intl.NumberFormat("id-ID").format(total)
        );
        $("#total_harga_input").val(total);
    }

    /**
     * Menambahkan baris makanan ke tabel pesanan.
     * @param {number} foodId - ID makanan yang akan ditambahkan.
     * @param {number} [qty=1] - Kuantitas awal.
     */
    function addFoodToTable(foodId, qty = 1) {
        if (!foodId) return;

        // Ambil data makanan dari variabel global yang di-passing dari Blade
        const food = window._foods[foodId];
        if (!food) {
            console.error(
                "Data makanan dengan ID " + foodId + " tidak ditemukan."
            );
            return;
        }

        // Cek jika makanan sudah ada di tabel, jika ya, tambahkan kuantitasnya
        const existingRow = $(`#table-food tr[data-food-id="${food.id}"]`);
        if (existingRow.length > 0) {
            const qtyInput = existingRow.find(".qty-input");
            const currentQty = parseInt(qtyInput.val()) || 0;
            qtyInput.val(currentQty + qty).trigger("change"); // trigger 'change' untuk kalkulasi ulang
            return;
        }

        // Jika belum ada, buat baris baru
        const rowHtml = `
            <tr data-food-id="${food.id}" data-harga="${food.harga}">
                <td>
                    ${food.nama}
                    <input type="hidden" name="foods_id[]" value="${food.id}">
                </td>
                <td>
                    <input type="number" name="qty[]" class="form-control form-control-sm qty-input" value="${qty}" min="1" style="width: 70px;">
                </td>
                <td class="text-right">Rp <span class="subtotal-display">0</span></td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-danger btn-icon rounded-circle remove-food-btn" title="Hapus">
                        <i class="fal fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        $("#table-food").append(rowHtml);
        calculateTotal(); // Hitung total setelah baris baru ditambahkan
    }

    // =================================================================
    // 3. EVENT HANDLERS (PENANGANAN AKSI PENGGUNA)
    // =================================================================

    // Event handler saat memilih makanan dari dropdown pencarian
    $("#search-food").on("change", function () {
        const foodId = $(this).val();
        addFoodToTable(foodId);
        $(this).val(null).trigger("change"); // Reset dropdown setelah memilih
    });

    // Event handler untuk menghapus baris makanan dari tabel (menggunakan event delegation)
    $("#table-food").on("click", ".remove-food-btn", function () {
        $(this).closest("tr").remove();
        calculateTotal();
    });

    // Event handler saat kuantitas diubah (input atau panah)
    $("#table-food").on("input change", ".qty-input", function () {
        // Validasi agar kuantitas tidak kurang dari 1
        if (parseInt($(this).val()) < 1) {
            $(this).val(1);
        }
        calculateTotal();
    });

    // --- LOGIKA UNTUK MODAL PILIH MENU ---

    // Event handler untuk pencarian di dalam modal menu
    $("#searchMenuInput").on("keyup", function () {
        const value = $(this).val().toLowerCase();
        $("#menu-list .menu-item").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Event handler saat mengklik tombol "Pilih" pada sebuah menu di modal (menggunakan delegation)
    $("#pilihMenuModal").on("click", ".pilih-menu-btn", function () {
        const menuId = $(this).data("menu-id");
        const menu = window.giziData.menus[menuId]; // Mengambil data dari objek global

        if (menu && menu.makanan_menu) {
            // Logika untuk menghitung kuantitas dan menambahkan ke tabel pesanan...
            const foodCounts = menu.makanan_menu.reduce((acc, item) => {
                if (item.aktif && item.makanan) {
                    // Pastikan item aktif dan relasi makanan ada
                    acc[item.makanan_id] = (acc[item.makanan_id] || 0) + 1;
                }
                return acc;
            }, {});

            for (const foodId in foodCounts) {
                addFoodToTable(foodId, foodCounts[foodId]);
            }
        }

        $("#pilihMenuModal").modal("hide"); // Tutup modal setelah memilih
    });

    // Event handler saat mengklik tombol "Pilih" pada sebuah menu di modal
    $(".pilih-menu-btn").on("click", function () {
        const menuId = $(this).data("menu-id");
        const menu = window._menus[menuId];

        if (menu && menu.makanan_menu) {
            // Hitung frekuensi setiap makanan dalam menu
            const foodCounts = menu.makanan_menu.reduce((acc, item) => {
                acc[item.makanan_id] = (acc[item.makanan_id] || 0) + 1;
                return acc;
            }, {});

            // Tambahkan setiap makanan ke tabel dengan kuantitas yang sesuai
            for (const foodId in foodCounts) {
                addFoodToTable(foodId, foodCounts[foodId]);
            }
        }

        $("#pilihMenuModal").modal("hide"); // Tutup modal setelah memilih
    });

    // Event handler untuk validasi form sebelum submit
    $("#form-order-gizi").on("submit", function (e) {
        if ($("#table-food tr").length === 0) {
            e.preventDefault(); // Mencegah form dikirim
            showErrorAlertNoRefresh(
                "Harap tambahkan minimal satu makanan ke dalam pesanan."
            );
        }
    });
});
