<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "order_id",
        "user_id",
        "customer_name",
        "order_date",
        "total_amount",
        "payment_method",
        "status",
        "payment_reference",
        "paid_at",
    ];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItems::class);
    }
}
