<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'table_id',
        'status',
        'total_price'
    ];
    public function items(){
        return $this->hasMany(OrderItem::class);
    }
    public function payment(){
        return $this->hasOne(Payment::class);
    }
    public function table(){
        return $this->belongsTo(Table::class);
    }
}
