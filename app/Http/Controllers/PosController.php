<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        $vouchers = Voucher::where('is_used', false)->get(['code']);

        return view('pos.index', compact('categories', 'products', 'vouchers'));
    }

    public function store(Request $request)
    {
        // Ubah validasi dari 'voucher_code' (string) menjadi 'voucher_codes' (array)
        $request->validate([
            'cart' => 'required|array',
            'voucher_codes' => 'nullable|array',
            'voucher_codes.*' => 'string'
        ]);

        $cart = $request->input('cart');
        $voucherCodes = $request->input('voucher_codes', []);

        DB::beginTransaction();
        try {
            // Validasi semua voucher yang dikirimkan
            if (!empty($voucherCodes)) {
                foreach ($voucherCodes as $code) {
                    $checkVoucher = DB::table('vouchers')->where('code', $code)->where('is_used', false)->first();
                    if (!$checkVoucher) {
                        return response()->json(['success' => false, 'message' => "Voucher {$code} sudah hangus atau tidak valid!"], 422);
                    }
                }
            }

            // Karena kolom 'voucher_code' di tabel orders biasanya bertipe string (menyimpan satu teks), 
            // Anda bisa menggabungkannya dengan koma, misal: "007, 008" atau menyesuaikan kolom database Anda.
            $voucherString = !empty($voucherCodes) ? implode(', ', $voucherCodes) : null;

            $order = Order::create([
                'voucher_code' => $voucherString,
                'total_items' => count($cart)
            ]);

            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty']
                ]);
            }

            // Tandai semua voucher yang digunakan menjadi status 'is_used' = true
            if (!empty($voucherCodes)) {
                DB::table('vouchers')->whereIn('code', $voucherCodes)->update([
                    'is_used' => true,
                    'used_at' => now()
                ]);
            }

            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Transaksi Berhasil!', 
                'order_id' => $order->id 
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function nota($id)
    {
        $order = Order::with('details')->findOrFail($id);
        return view('pos.nota', compact('order'));
    }

    public function history()
    {
        $orders = Order::with('details')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($orders);
    }
}