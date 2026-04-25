<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no',
        'product_id', 
        'customer_name', 
        'customer_phone', 
        'customer_address', 
        'status', 
        'total_price',
        'source'
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $year = date('Y');
            $latestOrder = static::whereYear('created_at', $year)->orderBy('created_at', 'desc')->first();
            
            if ($latestOrder && $latestOrder->order_no) {
                $lastNo = (int) substr($latestOrder->order_no, -4);
                $newNo = str_pad($lastNo + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNo = '0001';
            }

            $order->order_no = "ORD-{$year}-{$newNo}";
        });
    }

    public static $statuses = [
        'New Order',
        'Pending',
        'Pending Payment',
        'Processing',
        'Shipped',
        'Delivered',
        'Cancelled'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
