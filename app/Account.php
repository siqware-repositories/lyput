<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['desc','type','balance'];
    public function account_detail(){
        return $this->hasMany(AccountDetail::class);
    }
}
