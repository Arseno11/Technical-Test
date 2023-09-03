<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;


    // public const ORDERCODE = 'INV';

    // public static function generateCode()
    // {
    //     $dateCode = self::ORDERCODE . '/' . date('Ymd') . '/';

    //     return $dateCode;
    // }

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'subtotal',
        'name',
        'email',
        'alamat',
        'telepon',
    ];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}