<?php

namespace App\Http\Controllers;

use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\User;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseStockAdjustment;
use App\Models\WarehouseStockAdjustmentItems;
use App\Models\WarehouseStockAdjustmentUsers;
use App\Services\GoodsStockService;
use App\Services\IncreaseDecreaseStockArguments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class WarehouseStockAdjustmentController extends Controller
{
    protected GoodsStockService $goodsStockService;

    public function __construct(GoodsStockService $goodsStockService)
    {
        $this->goodsStockService = $goodsStockService;
        $this->goodsStockService->controller = $this::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseStockAdjustment::query()->with(['items', 'items.stored', 'items.stored.pbi']);
        $filters = ['gudang_id', 'kode_sa'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->filled('tanggal_sa')) {
            $dateRange = explode(' - ', $request->tanggal_sa);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('tanggal_sa', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('nama_barang')) {
            $query->whereHas('items.stored.pbi', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $sa = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $sa = WarehouseStockAdjustment::all();
        }

        $auth_users = \App\Models\OtorisasiUser::with('user.employee')
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('otorisasi_type', 'Stock Adjustment')
            ->get();

        return view('pages.simrs.warehouse.revaluasi-stock.stock-adjustment.index', [
            'sas' => $sa,
            'gudangs' => WarehouseMasterGudang::all(),
            'auth_users' => $auth_users,
        ]);
    }

    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah!', // Return a JSON response with an error message
            ], 401); // Return a 401 Unauthorized status code
        }

        // Attempt Zimbra login if local authentication fails
        if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
            // Zimbra authentication successful
            $payload = [
                'user_id' => $user->id,
                'exp' => time() + 3600,
            ];

            $token = Crypt::encryptString(json_encode($payload));

            return response([
                'success' => true,
                'message' => 'Login berhasil!',
                'token' => $token, // Return a JSON response with a success message
            ], 200); // Return a 200 OK status code
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah!', // Return a JSON response with an error message
            ], 401); // Return a 401 Unauthorized status code
        }
    }

    private function zimbraLogin($email, $password)
    {
        $data = [
            'Header' => [
                'context' => [
                    '_jsns' => 'urn:zimbra',
                    'userAgent' => ['name' => 'curl', 'version' => '8.8.15'],
                ],
            ],
            'Body' => [
                'AuthRequest' => [
                    '_jsns' => 'urn:zimbraAccount',
                    'account' => ['_content' => $email, 'by' => 'name'],
                    'password' => $password,
                ],
            ],
        ];

        try {
            $encodedData = json_encode($data);

            $url = 'https://webmail.livasya.com/service/soap';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type:application/json',
            ]);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);

            $mark = 'AUTH_FAILED';

            if (strpos($result, $mark) !== false) {
                return false; // Autentikasi gagal
            } else {
                // Autentikasi berhasil
                // Anda mungkin ingin melakukan sesuatu di sini, seperti memproses respons
                // atau mengembalikan informasi tambahan
                return true;
            }
        } catch (\Exception $e) {
            // Tangani kesalahan saat menjalankan permintaan cURL
            return false;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($token)
    {
        $data = json_decode(Crypt::decryptString($token), true);

        if ($data['exp'] < time()) {
            abort(401, 'Token expired');
        }

        return view('pages.simrs.warehouse.revaluasi-stock.stock-adjustment.partials.popup-add-sa', [
            'gudangs' => WarehouseMasterGudang::all(),
            'token' => $token,
        ]);
    }

    public function get_items($token, $gudang_id)
    {
        $data = json_decode(Crypt::decryptString($token), true);

        if ($data['exp'] < time()) {
            abort(401, 'Token expired');
        }

        $sif = StoredBarangFarmasi::query()->with(['pbi', 'pbi.satuan', 'pbi.pb', 'pbi.item',  'pbi.item.golongan', 'pbi.item.kategori']);
        $sinf = StoredBarangNonFarmasi::query()->with(['pbi', 'pbi.satuan', 'pbi.pb', 'pbi.item',  'pbi.item.golongan', 'pbi.item.kategori']);

        $sif->where('gudang_id', $gudang_id);
        $sinf->where('gudang_id', $gudang_id);

        // $sif->where("qty" , ">" , 0);
        // $sinf->where("qty" , ">" , 0);

        $sif->whereHas('pbi', function ($q) {
            // where "tanggal_exp" > now()
            // or where "tanggal_exp" is null
            $q->where(function ($q) {
                $q->where('tanggal_exp', '>', now());
                $q->orWhereNull('tanggal_exp');
            });
        });

        $sinf->whereHas('pbi', function ($q) {
            // where "tanggal_exp" > now()
            // or where "tanggal_exp" is null
            $q->where(function ($q) {
                $q->where('tanggal_exp', '>', now());
                $q->orWhereNull('tanggal_exp');
            });
        });

        $sif = $sif->get();
        $sinf = $sinf->get();

        return response()->json([
            'pharmacy' => $sif,
            'non_pharmacy' => $sinf,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseStockAdjustment $warehouseStockAdjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($gudang_id, $barang_id, $satuan_id, $type, $token)
    {
        $data = json_decode(Crypt::decryptString($token), true);

        if ($data['exp'] < time()) {
            abort(401, 'Token expired');
        }
        $satuan = WarehouseSatuanBarang::findOrFail($satuan_id);

        $barang = WarehouseBarangFarmasi::query();
        $query = StoredBarangFarmasi::query()->with(['pbi', 'pbi.pb', 'pbi.item', 'pbi.pb.supplier']);
        if ($type == 'nf') {
            $query = StoredBarangNonFarmasi::query()->with(['pbi', 'pbi.pb', 'pbi.item', 'pbi.pb.supplier']);
            $barang = WarehouseBarangNonFarmasi::query();
        }

        $query->where('gudang_id', $gudang_id);

        $query->whereHas('pbi', function ($q) use ($barang_id, $satuan_id) {
            $q->where('barang_id', $barang_id)
                ->where('satuan_id', $satuan_id);
        });

        $sis = $query->get();

        return view('pages.simrs.warehouse.revaluasi-stock.stock-adjustment.partials.popup-edit-sa', [
            'sis' => $sis,
            'token' => $token,
            'gudang' => WarehouseMasterGudang::where('id', $gudang_id)->first(),
            'barang' => $barang->where('id', $barang_id)->first(),
            'satuan' => $satuan,
            'type' => $type,
        ]);
    }

    private function generate_sa_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = WarehouseStockAdjustment::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return $count . '/ADJ/' . $year . $month;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $validatedData1 = $request->validate([
            'user_id' => 'required|exists:users,id',
            'barang_f_id' => 'nullable|exists:warehouse_barang_farmasi,id',
            'barang_nf_id' => 'nullable|exists:warehouse_barang_non_farmasi,id',
            'satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            'gudang_id' => 'required|exists:warehouse_master_gudang,id',
            'tanggal_sa' => 'required|date',
        ]);

        $validatedData2 = $request->validate([
            'token' => 'required|string',
            'qty.*' => 'required|integer',
            'si_id.*' => 'required|integer',
            'type' => 'required|in:f,nf',
        ]);

        // dd($request->all());
        $data = json_decode(Crypt::decryptString($validatedData2['token']), true);
        if ($data['exp'] < time()) {
            abort(401, 'Token expired');
        }
        $validatedData1['authorized_user_id'] = $data['user_id'];
        $validatedData1['kode_sa'] = $this->generate_sa_code();

        DB::beginTransaction();
        try {
            $sa = WarehouseStockAdjustment::create($validatedData1);
            $user = User::findOrFail($validatedData1['authorized_user_id']);

            foreach ($validatedData2['qty'] as $si_id => $qty) {
                $query = StoredBarangFarmasi::query();
                if ($validatedData2['type'] == 'nf') {
                    $query = StoredBarangNonFarmasi::query();
                }

                $stored_barang = $query->find($si_id);
                if (! $stored_barang) {
                    throw new \Exception('Stored barang not found');
                }

                $delta = $qty - $stored_barang->qty;

                if ($delta == 0) {
                    continue;
                }

                WarehouseStockAdjustmentItems::create([
                    'sa_id' => $sa->id,
                    'si_' . $validatedData2['type'] . '_id' => $stored_barang->id,
                    'qty' => $delta,
                ]);

                // $stored_barang->update([
                //     "qty" => $qty
                // ]);
                // $stored_barang->save();

                // use the GoodsStockService
                $args = new IncreaseDecreaseStockArguments($user, $sa, $stored_barang, $delta);
                if ($delta < 0) {
                    $this->goodsStockService->decreaseStock($args);
                } else {
                    $this->goodsStockService->increaseStock($args);
                }
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseStockAdjustment $warehouseStockAdjustment)
    {
        //
    }
}
