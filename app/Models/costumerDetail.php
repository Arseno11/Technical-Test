<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class costumerDetail extends Model
{
    use HasFactory;


    protected $table = 'detail_costumer';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'alamat',
        'phone',
    ];
}
