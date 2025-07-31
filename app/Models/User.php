<?php

namespace App\Models;

use App\Models\Inventaris\MaintenanceBarang;
use App\Models\Inventaris\ReportBarang;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\OrderTindakanMedis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles {
        HasRoles::hasPermissionTo as traitHasPermissionTo;
    }

    // use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * Override hasPermissionTo method to allow super admin to bypass permissions
     *
     * @param string|int|\Spatie\Permission\Contracts\Permission $permission
     * @param string|null $guardName
     * @return bool
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if ($this->hasRole('super admin')) {
            return true;
        }

        // Call hasPermissionTo from HasRoles trait
        return $this->traitHasPermissionTo($permission, $guardName);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function reportBarang()
    {
        return $this->hasMany(ReportBarang::class);
    }

    public function maintenance()
    {
        return $this->hasMany(MaintenanceBarang::class);
    }
    public function survei_kebersihan_kamar()
    {
        return $this->hasMany(SurveiKebersihanKamar::class);
    }

    public function order_tindakan_medis()
    {
        return $this->hasMany(OrderTindakanMedis::class);
    }

    public function bilingan()
    {
        return $this->hasMany(Bilingan::class);
    }

    public function checklist_harian()
    {
        return $this->hasMany(ChecklistHarian::class);
    }

    public function order_radiologi()
    {
        return $this->hasMany(OrderRadiologi::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function laporan_internal()
    {
        return $this->hasMany(LaporanInternal::class);
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function lastSeenHuman()
    {
        return $this->last_seen ? \Carbon\Carbon::parse($this->last_seen)->diffForHumans() : 'Belum pernah online';
    }

    public function pr_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestPharmacy::class, 'user_id');
    }

    public function pr_non_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestNonPharmacy::class, 'user_id');
    }

    public function app_pr_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestPharmacy::class, 'app_user_id');
    }

    public function app_pr_non_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseRequestNonPharmacy::class, 'app_user_id');
    }

    public function po_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseOrderPharmacy::class, 'user_id');
    }

    public function po_non_pharmacy()
    {
        return $this->hasMany(ProcurementPurchaseOrderNonPharmacy::class, 'user_id');
    }

    public function rb()
    {
        return $this->hasMany(WarehouseReturBarang::class, 'user_id');
    }
}
