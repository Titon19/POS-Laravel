<?php

use App\Models\Order;
use App\Models\OrderItems;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderService {
    public function findOrders(Request $request){
        $perPage = $request->query("per_page", 10);
        $orders = Order::paginate($perPage, ["*"]);
        return $orders;
    }

    public function findOrderById(Int $id){

        $order = Order::with(["orderItems.product", "user"])->findOrFail($id);
        return $order;
    }


    public function generateOrderId(): string
    {
        $today = Carbon::now();

        $day   = $today->format('d');
        $month = $today->format('m');
        $year  = $today->format('y');

        $prefix = 'TRX-';

        $lastCode = Order::whereYear('created_at', $today->year)
            ->orderBy('order_id', 'desc')
            ->value('order_id');

        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, -2);
            $sequence = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $sequence = '01';
        }   

        return "{$prefix}-{$day}{$month}{$year}{$sequence}";
    }
    
    public function createOrder(array $data) 
    {
        $data['order_id'] = $this->generateOrderId();
        $createdCategory = Order::create($data);

        return $createdCategory;
    }


    public function updateOrder(int $id, array $data){
        $order = $this->findOrderById($id);
        $order->update($data);
        return $order;
    }

}