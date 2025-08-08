<?php

namespace App\Models\Keuangan;

use App\Models\Keuangan\Bank;
use App\Models\User;
use App\Models\WarehouseSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranApSupplierHeader extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel database.
     */
    protected $table = 'pembayaran_ap_supplier_headers';

    /**
     * Atribut yang dikecualikan dari mass assignment.
     */
    protected $guarded = ['id'];
    protected $casts = [
        // 'nama_kolom' => 'tipe_data'
        'tanggal_pembayaran' => 'datetime', // atau 'date' jika tidak ada komponen waktu
        'total_pembayaran' => 'float', // Contoh lain, cast total ke float
        'pembulatan' => 'float', // Contoh lain
    ];
    /**
     * Relasi ke model PembayaranApSupplierDetail (One to Many).
     * Satu header pembayaran memiliki banyak detail.
     */
    public function details()
    {
        return $this->hasMany(PembayaranApSupplierDetail::class, 'pembayaran_ap_header_id');
    }

    /**
     * Relasi ke model WarehouseSupplier (Many to One).
     * Satu pembayaran dimiliki oleh satu supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(WarehouseSupplier::class, 'supplier_id');
    }

    /**
     * Relasi ke model KasBank (Many to One).
     * Satu pembayaran menggunakan satu akun kas/bank.
     * Pastikan nama model `KasBank` dan namespace-nya sudah benar.
     */
    public function kasBank()
    {
        return $this->belongsTo(Bank::class, 'kas_bank_id');
    }

    /**
     * Relasi ke model User (Many to One).
     * Satu pembayaran dientri oleh satu user.
     */
    public function userEntry()
    {
        return $this->belongsTo(User::class, 'user_entry_id');
    }

    public function apSupplier()
    {
        return $this->belongsTo(ApSupplierHeader::class, 'ap_supplier_header_id');
    }
}
