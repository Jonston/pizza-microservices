<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Order
 *
 * @property string $id
 * @property OrderStatusEnum $status
 */
class Order extends Model
{
    use HasUlids;

    protected $fillable = [
        'id',
        'status'
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
