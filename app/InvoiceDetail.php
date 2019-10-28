<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = ['invoice_id', 'stock_detail_id', 'amount', 'qty'];
    public function employee(){
        return $this->belongsTo(Employee::class,'stock_detail_id','id');
    }
    public function stock_detail(){
        return $this->belongsTo(StockDetail::class);
    }
}
