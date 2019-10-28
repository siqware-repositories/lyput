<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    protected $fillable = ['account_id','invoice_id', 'desc','balance'];
    public function account(){
        return $this->belongsTo(Account::class);
    }
}
