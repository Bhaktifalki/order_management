<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'products'; // Specify the correct table name

    protected $fillable = ['order_id', 'name', 'qty', 'amount', 'total'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
