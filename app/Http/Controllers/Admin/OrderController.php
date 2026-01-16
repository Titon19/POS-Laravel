<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OrderService;

class OrderController extends Controller
{
    use ApiResponse;

    protected $orderService;

    public function __construct(OrderService $orderService){
        $this->orderService = $orderService;
    }

    public function index(Request $request){
          try {
            $orders = $this->orderService->findOrders($request);

            $paginated = $orders->toArray();

            $data = $paginated['data'];

            $meta = [
                'current_page' => $paginated['current_page'],
                'last_page'    => $paginated['last_page'],
                'per_page'     => $paginated['per_page'],
                'total'        => $paginated['total'],
                'next_page_url' => $paginated['next_page_url'],
                'prev_page_url' => $paginated['prev_page_url'],
            ];

            return $this->successResponse($data, $meta, "Successfully get orders");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to get orders");
        }
    }

    public function show($id){
        try {
            $order = $this->orderService->findOrderById($id);

            return $this->successResponse($order, null, "Successfully show order");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, "Failed to show order");
        }
    }



}
