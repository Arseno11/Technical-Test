<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostumerController extends Controller
{
    public function index()
    {

        $products = Product::latest();

        $products = Product::orderBy('id', 'DESC')->where('status', 1)->get();
        $products = Product::paginate(10);
        return view('frontend.home', compact('products'));
    }


    public function chart()
    {
        return view('frontend.charts');
    }
}
