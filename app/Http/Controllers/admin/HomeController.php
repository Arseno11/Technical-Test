<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\orderItem;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $totalSale = orderItem::where('status', 'Paid')->sum('total'); // Hitung total penjualan
        $totalOrder = Order::count(); // Hitung total pesanan
        $totalCustomer = User::where('role', '2')->count(); // Hitung total pelanggan

        return view('admin.dashboard', compact('admin', 'totalSale', 'totalOrder', 'totalCustomer'));
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}