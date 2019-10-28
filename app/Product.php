<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['desc'];
    public function stock_details(){
        return $this->hasMany(StockDetail::class)->where('status',1);
    }
}
