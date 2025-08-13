<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property string $id
 * @property string $name
 * @property float $price
 * @property string $image
 */
class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'name',
        'price',
        'image'
    ];
}
