<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'product_id',
        'code_order',
        'name_product',
        'quantity',
        'total',
        'price',
        'status',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
