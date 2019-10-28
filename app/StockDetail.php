<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockDetail extends Model
{
    public static $search_term;
    protected $fillable = ['stock_id', 'product_id', 'qty', 'stock_qty', 'pur_value', 'sale_value','is_group'];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public static function get_search($term){
        self::$search_term = $term;
    }
    public function product_stock_search(){
        $term = self::$search_term;
        return $this->belongsTo(Product::class,'product_id','id')->where('desc','like',"%$term%");
    }
}
