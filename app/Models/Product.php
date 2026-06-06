<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image',
        'status'
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function orderItem(){
        return $this->hasMany(OrderItem::class);
    }
}
