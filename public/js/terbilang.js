// Konten untuk file terbilang.js
function terbilang(bilangan) {
    bilangan = String(bilangan);
    var angka = new Array(
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0",
        "0"
    );
    var kata = new Array(
        "",
        "satu",
        "dua",
        "tiga",
        "empat",
        "lima",
        "enam",
        "tujuh",
        "delapan",
        "sembilan"
    );
    var tingkat = new Array("", "ribu", "juta", "milyar", "triliun");

    var panjang_bilangan = bilangan.length;

    /* pengujian panjang bilangan */
    if (panjang_bilangan > 15) {
        kaLimat = "Diluar Batas";
        return kaLimat;
    }

    /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
    for (i = 1; i <= panjang_bilangan; i++) {
        angka[i] = bilangan.substr(-i, 1);
    }

    var i = 1;
    var j = 0;
    var kaLimat = "";

    /* mulai proses iterasi terhadap array angka */
    while (i <= panjang_bilangan) {
        subkaLimat = "";
        kata1 = "";
        kata2 = "";
        kata3 = "";

        /* untuk ratusan */
        if (angka[i + 2] != "0") {
            if (angka[i + 2] == "1") {
                kata1 = "seratus";
            } else {
                kata1 = kata[angka[i + 2]] + " ratus";
            }
        }

        /* untuk puluhan atau belasan */
        if (angka[i + 1] != "0") {
            if (angka[i + 1] == "1") {
                if (angka[i] == "0") {
                    kata2 = "sepuluh";
                } else if (angka[i] == "1") {
                    kata2 = "sebelas";
                } else {
                    kata2 = kata[angka[i]] + " belas";
                }
            } else {
                kata2 = kata[angka[i + 1]] + " puluh";
            }
        }

        /* untuk satuan */
        if (angka[i] != "0") {
            if (angka[i + 1] != "1") {
                kata3 = kata[angka[i]];
            }
        }

        /* pengujian angka nol */
        if (angka[i] != "0" || angka[i + 1] != "0" || angka[i + 2] != "0") {
            subkaLimat =
                kata1 + " " + kata2 + " " + kata3 + " " + tingkat[j] + " ";
        }

        /* gabungkan kalimat */
        kaLimat = subkaLimat + kaLimat;

        i = i + 3;
        j = j + 1;
    }

    /* mengganti satu ribu menjadi seribu jika diperlukan */
    if (angka[5] == "0" && angka[6] == "0") {
        kaLimat = kaLimat.replace("satu ribu", "seribu");
    }

    return kaLimat.trim();
}
