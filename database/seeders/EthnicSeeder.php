<?php

namespace Database\Seeders;

use App\Models\SIMRS\Ethnic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EthnicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ethnics = [
            'Aceh',
            'Alas',
            'Alor',
            'Ambon',
            'Aneuk Jame',
            'Arab',
            'Arfak',
            'Asmat',
            'Atoni',
            'Auwye/Mee',
            'Bajao',
            'Bali',
            'Banggai',
            'Bangka',
            'Banjar',
            'Banten',
            'Batak',
            'Bawean',
            'Belitung',
            'Betawi',
            'Biak Numfor',
            'Bima',
            'Bugis',
            'Buol',
            'Buton',
            'Cirebon',
            'Dani',
            'Dauwa',
            'Daya',
            'Dayak',
            'Dompu',
            'Duri',
            'Enim',
            'Flores',
            'Galela',
            'Gayo',
            'Gorontalo',
            'Jawa',
            'Kaili',
            'Kei',
            'Kerinci',
            'Komering',
            'Kutai',
            'Lamaholot',
            'Lampung',
            'Lauje',
            'Lembak',
            'Lio',
            'Luwu',
            'Madura',
            'Makasar',
            'Makian',
            'Mamasa',
            'Mamuju',
            'Mandar',
            'Manggarai',
            'Mbojo',
            'Melayu',
            'Mentawai',
            'Minahasa',
            'Minangkabau',
            'Mongondow',
            'Moni',
            'Muna',
            'Musi',
            'Ngada',
            'Ngalik',
            'Nias',
            'Ogan',
            'Osing/Using',
            'Palembang',
            'Pamoa',
            'Pasir',
            'Rambang',
            'Rawas',
            'Rejang',
            'Rote',
            'Saluan',
            'Sangir',
            'Saparua',
            'Sasak',
            'Sawu',
            'Selayar',
            'Seram',
            'Simeulue',
            'Sula',
            'Sumba',
            'Sumbawa',
            'Sunda',
            'Talaud',
            'Tanimbar',
            'Ternate',
            'Tidore',
            'Timor Leste',
            'Tionghoa',
            'Tobelo',
            'Tolaki',
            'Tomini',
            'Toraja',
            'Yapen'
        ];

        foreach ($ethnics as $ethnic) {
            DB::table('ethnics')->insert([
                'name' => $ethnic,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
