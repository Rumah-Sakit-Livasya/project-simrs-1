<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Models\StoredBarangFarmasi;
use App\Models\StoredBarangNonFarmasi;
use App\Models\User;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehousePenerimaanBarangFarmasiItems;
use App\Models\WarehousePenerimaanBarangNonFarmasiItems;
use Illuminate\Database\Eloquent\Model;

enum GoodsType: string
{
    case NonPharmacy = 'nf';
    case Pharmacy    = 'f';
}

class CreateStockArguments
{
    public function __construct(
        public User $user,
        public Model $source,
        public GoodsType $type,
        public WarehouseMasterGudang $warehouse,
        public WarehousePenerimaanBarangFarmasiItems|WarehousePenerimaanBarangNonFarmasiItems $pbi,
        public string $keterangan = '',
        public int $qty,
    ) {}
}

class IncreaseDecreaseStockArguments
{
    public function __construct(
        public User $user,
        public Model $source,
        public StoredBarangFarmasi|StoredBarangNonFarmasi $item,
        public int $qty,
        public string $keterangan = '',
    ) {}
}

class MoveStockArguments
{
    public WarehouseMasterGudang $warehouse_source;
    public int $qty;

    public function __construct(
        public User $user,
        public Model $source,
        public StoredBarangFarmasi|StoredBarangNonFarmasi $item,
        public WarehouseMasterGudang $warehouse_destination,
        public string $keterangan = '',
    ) {
        $this->warehouse_source = WarehouseMasterGudang::findOrFail($item->gudang_id);
        $this->qty = $item->qty;
    }
}

class TransferStockArguments
{
    public function __construct(
        public User $user,
        public Model $source,
        public StoredBarangFarmasi|StoredBarangNonFarmasi $itemA,
        public StoredBarangFarmasi|StoredBarangNonFarmasi $itemB,
        public int $qty,
        public string $keterangan = '',
    ) {}
}

class GoodsStockService
{
    public string $controller;

    /**
     * Creates a new stock item based on the provided arguments.
     *
     * @param CreateStockArguments $args The arguments containing details for creating the stock item.
     * @return Model The created stock model instance.
     */
    public function createStock(CreateStockArguments $args): Model
    {
        $itemClass = $args->type === GoodsType::Pharmacy
            ? StoredBarangFarmasi::class
            : StoredBarangNonFarmasi::class;

        /** @var StoredBarangFarmasi|StoredBarangNonFarmasi $item */
        $item = new $itemClass();
        $item->gudang_id = $args->warehouse->id;
        $item->pbi_id    = $args->pbi->id;
        $item->qty       = $args->qty;
        $item->save();

        $this->logTransaction([
            'stock_id'          => $item->id,
            'stock_model'       => $item::class,
            'source_id'         => $args->source->id,
            'source_model'      => $args->source::class,
            'source_controller' => $this->controller,
            'event_type'        => 'create',
            'transaction_type'  => 'in',
            'after_qty'         => $args->qty,
            'after_gudang_id'   => $args->warehouse->id,
            'performed_by'      => $args->user->id,
            'keterangan'        => $args->keterangan
        ]);

        return $item;
    }

    /**
     * Increases the quantity of a stock item.
     *
     * @param IncreaseDecreaseStockArguments $args The arguments containing details for increasing the stock.
     */
    public function increaseStock(IncreaseDecreaseStockArguments $args): void
    {
        $this->updateQuantity($args->item, +$args->qty, 'in', $args);
    }

    /**
     * Decreases the quantity of a stock item, throwing an exception if there is insufficient stock.
     *
     * @param IncreaseDecreaseStockArguments $args The arguments containing details for decreasing the stock.
     */
    public function decreaseStock(IncreaseDecreaseStockArguments $args): void
    {
        if ($args->item->qty < $args->qty) {
            throw new \Exception("Stock tidak cukup");
        }

        $this->updateQuantity($args->item, -abs($args->qty), 'out', $args);
    }

    /**
     * Moves a stock item from one warehouse to another.
     *
     * @param MoveStockArguments $args The arguments containing details for moving the stock.
     */
    public function moveStock(MoveStockArguments $args): void
    {
        $beforeGudang = $args->warehouse_source->id;
        $afterGudang  = $args->warehouse_destination->id;

        $args->item->gudang_id = $afterGudang;
        $args->item->save();

        $this->logTransaction([
            'stock_id'          => $args->item->id,
            'stock_model'       => $args->item::class,
            'source_id'         => $args->source->id,
            'source_model'      => $args->source::class,
            'source_controller' => $this->controller,
            'event_type'        => 'update',
            'before_gudang_id'  => $beforeGudang,
            'after_gudang_id'   => $afterGudang,
            'after_qty'         => $args->qty,
            'performed_by'      => $args->user->id,
            'keterangan'        => $args->keterangan
        ]);
    }

    /**
     * Transfers a quantity of stock from one item to another, ensuring both items are of the same type and PBI.
     *
     * @param TransferStockArguments $args The arguments containing details for transferring the stock.
     */
    public function transferStock(TransferStockArguments $args): void
    {
        $this->ensureSameTypeAndPbi($args->itemA, $args->itemB);

        // Debit A, credit B
        $this->updateQuantity($args->itemA, -$args->qty, 'out', $args);
        $this->updateQuantity($args->itemB, +$args->qty, 'in', $args);
    }

    // ——— PRIVATE HELPERS —————————————————————————————————————————————

    private function updateQuantity(
        StoredBarangFarmasi|StoredBarangNonFarmasi $item,
        int $delta,
        string $transactionType,
        object $args
    ): void {
        $before = $item->qty;
        $item->qty += $delta;
        $item->save();

        $this->logTransaction([
            'stock_id'          => $item->id,
            'stock_model'       => $item::class,
            'source_id'         => $args->source->id,
            'source_model'      => $args->source::class,
            'source_controller' => $this->controller,
            'event_type'        => 'update',
            'transaction_type'  => $transactionType,
            'before_qty'        => $before,
            'after_qty'         => $item->qty,
            'after_gudang_id'   => $item->gudang_id,
            'performed_by'      => $args->user->id,
            'keterangan'        => $args->keterangan
        ]);
    }

    private function ensureSameTypeAndPbi(object $a, object $b): void
    {
        if (get_class($a) !== get_class($b)) {
            throw new \Exception("Item A and Item B must be of the same type");
        }
        if ($a->pbi->id !== $b->pbi->id) {
            throw new \Exception("Item A and Item B must belong to the same PBI");
        }
    }

    private function logTransaction(array $data): void
    {
        StockTransaction::create($data);
    }
}
