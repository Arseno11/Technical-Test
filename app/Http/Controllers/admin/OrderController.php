<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\orderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        // $orders = OrderItem::with(['orders:id,name,email,telepon'])
        //     ->select('code_order', 'total', 'status')
        //     ->paginate(10);

        $orders = DB::table('order_item')
            ->join('orders', 'order_item.order_id', '=', 'orders.id')
            ->select('order_item.code_order', 'order_item.total', 'order_item.created_at', 'order_item.quantity', 'order_item.status', 'orders.email', 'orders.name', 'orders.telepon')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }
}