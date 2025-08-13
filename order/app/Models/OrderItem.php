<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class OrderItem
 *
 * @property string $id
 * @property string $order_id
 * @property string $product_id
 * @property int $quantity
 * @property Order $order
 * @property Product $product
 */
class OrderItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'id' => 'string',
        'order_id' => 'string',
        'product_id' => 'string',
        'quantity' => 'integer'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
