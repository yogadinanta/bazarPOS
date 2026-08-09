<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // Mengizinkan kolom ini diisi secara massal saat insert data
    protected $fillable = [
        'voucher_code',
        'total_items',
        'order_type' 
    ];

    // Relasi ke detail order (Satu order memiliki banyak detail menu)
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}