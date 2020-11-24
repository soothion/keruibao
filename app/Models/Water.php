<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Water extends Model
{
    protected $fillable = ['inout','type','inout','batch_id', 'check_id','amount','date', 'status', 'description','image'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
