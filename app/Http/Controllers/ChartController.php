<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\orderItem;
use Illuminate\Http\Request;
use App\Models\costumerDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
    public function addToChart(Request $request)
    {
        $product = Product::find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ]);
        }

        if (Cart::count() > 0) {

            $cartContent = Cart::content();
            $productAlreadyExits = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExits = true;
                }
            }

            if ($productAlreadyExits == false) {
                Cart::add($product->id, $product->name, 1, $product->price,  ['images' => $product->image, 'stock' => $product->stock]);
                $status = true;
                $message = $product->name . ' added to chart';
            } else {
                $status = false;
                $message = $product->name . ' already added in chart';
            }
        } else {


            Cart::add($product->id, $product->name, 1, $product->price, ['images' => $product->image, 'stock' => $product->stock]);
            $status = true;
            $message = $product->name . ' added to chart';
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function chart(Request $request)
    {

        $stock = Product::first()->stock;

        $cartContent = Cart::content();

        $data['cartContent'] = $cartContent;

        return view('frontend.charts', compact('stock'), $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;
        // Ambil item keranjang yang akan diperbarui
        $cartItem = Cart::get($rowId);

        // Ambil stok produk dari tabel produk berdasarkan ID produk
        $product = Product::find($cartItem->id);

        if ($qty <= $product->stock) {
            // Kuantitas yang dimasukkan tidak melebihi stok produk, maka lanjutkan pembaruan
            Cart::update($rowId, $qty);
            $message = "Cart Updated Successfully";
        } else {
            // Kuantitas melebihi stok produk, beri pesan kesalahan
            $message = "Jumlah item melebihi stok";
        }

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }


    public function deleteItem(Request $request)
    {
        $rowId = $request->rowId;
        $infoItem = Cart::get($rowId);

        if ($infoItem == null) {
            $errorMessage = 'Item not found';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        // Ambil stok produk dari tabel produk berdasarkan ID produk
        $product = Product::find($request->id);

        if ($product && $product->stock === 0) {
            $errorMessage = 'Item cannot be deleted because stock is 0';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($rowId);

        $message = 'Item deleted successfully';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout(Request $request)
    {

        if (Cart::count() == 0) {

            session()->flash('error', 'Cart is empty');
            return redirect()->route('costumer.chart')->with('error', 'Cart is empty');
        }

        if (Auth::check() === false) {

            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('login');
        }

        session()->forget('url.intended');

        // Ambil ID pesanan terbaru dari tabel order_item
        $order = OrderItem::latest()->value('order_id');

        return view('frontend.checkout', compact('order'));
    }

    public function prosesCheckout(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'telepon' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user = Auth::user();

        costumerDetail::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'user_id' => $user->id,
                'nama' => $request->name,
                'email' => $request->email,
                'alamat' =>    $request->address,
                'telepon' => $request->phone,
            ]
        );


        $subtotal = Cart::subtotal();

        $order = new Order;
        $order->user_id = $user->id;
        $order->subtotal = $subtotal;
        $order->name = $request->name;
        $order->email = $request->email;
        $order->alamat = $request->address;
        $order->telepon = $request->telepon;
        $order->save();


        foreach (Cart::content() as $item) {
            $orderItem = new orderItem;
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->code_order = generateOrderCode() . $order->id;
            $orderItem->name_product = $item->name;
            $orderItem->quantity = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->total = $item->price * $item->qty;
            $orderItem->status = 'Unpaid';
            $orderItem->save();
        }

        // Inisialisasi array untuk item_details
        $itemDetails = [];

        // Loop melalui produk dalam Cart
        foreach (Cart::content() as $item) {
            // Informasi produk
            $productInfo = [
                'id' => $item->id, // ID produk unik
                'price' => $item->price, // Harga produk
                'quantity' => $item->qty, // Kuantitas produk
                'name' => $item->name, // Nama produk
            ];

            // Tambahkan informasi produk ke dalam array item_details
            $itemDetails[] = $productInfo;
        }

        // Bagian kedua:  Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;


        // Bagian keempat: Buat parameter untuk Snap
        $params = [
            'transaction_details' => [
                'order_id' => $orderItem->code_order, // Menggunakan ID pesanan 
                'gross_amount' => $orderItem->total, // Menggunakan total price pesanan
            ],
            'customer_details' => [
                'first_name' => $order->name,
                'email' => $order->email,
                'phone' => $order->telepon,
            ],
            'item_details' => $itemDetails
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        session()->flash('success', 'Order Berhasil');

        return response()->json([
            'status' => true,
            'message' => 'Order Berhasil',
            'snapToken' => $snapToken,
        ]);
    }

    public function handleCallback()
    {
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $order = OrderItem::where('code_order', $order_id)->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        if ($transaction == 'settlement') {
            $product = Product::find($order->product_id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            $newStock = $product->stock - $order->quantity;

            if ($newStock >= 0) {
                $product->update(['stock' => $newStock]);

                // Update status pesanan menjadi 'Paid'
                $order->update(['status' => 'Paid']);

                return response()->json([
                    'status' => true,
                    'message' => 'Status pesanan diperbarui menjadi "Paid"'
                ]);
            } else if ($newStock <= 0) {
                $product->update(['status' => 0]);
            } else {
                session()->flash('error', 'Stok habis');

                return response()->json([
                    'status' => false,
                    'message' => 'Stok habis'
                ]);
            }
        } else if ($transaction == 'capture' && $type == 'credit_card' && $fraud == 'accept') {
            // Implementasikan kode untuk transaksi yang berhasil ditangkap (capture) di sini
            echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
        } else if ($transaction == 'pending') {
            // Implementasikan kode untuk transaksi yang berstatus 'pending' di sini
            echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
        } else if ($transaction == 'deny') {
            // Implementasikan kode untuk transaksi yang 'deny' di sini
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
        } else if ($transaction == 'expire') {
            // Implementasikan kode untuk transaksi yang 'expire' di sini
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
        } else if ($transaction == 'cancel') {
            // Implementasikan kode untuk transaksi yang 'cancel' di sini
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
        } else {
            // Implementasikan kode untuk kasus lainnya jika diperlukan
            echo "Unknown transaction status: " . $transaction;
        }
    }
}