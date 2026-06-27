<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'tabe_number',
        'qr_code',
        'status' 
    ];
    public function order(){
        return $this->hasMany(Order::class);
    }
}
