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
        // Menggunakan paginate (misal 20 produk per halaman) agar refresh halaman POS tidak berat
        $products = Product::paginate(20); 
        $vouchers = Voucher::where('is_used', false)->get(['code']);

        return view('pos.index', compact('categories', 'products', 'vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'voucher_codes' => 'nullable|array',
            'voucher_codes.*' => 'string',
            'order_type' => 'required|string|in:dine_in,take_away'
        ]);

        $cart = $request->input('cart');
        $voucherCodes = $request->input('voucher_codes', []);
        $orderType = $request->input('order_type');

        DB::beginTransaction();
        try {
            // Validasi ketersediaan setiap voucher di database
            if (!empty($voucherCodes)) {
                foreach ($voucherCodes as $code) {
                    $checkVoucher = DB::table('vouchers')->where('code', $code)->where('is_used', false)->first();
                    if (!$checkVoucher) {
                        return response()->json(['success' => false, 'message' => "Voucher {$code} sudah hangus atau tidak valid!"], 422);
                    }
                }
            }

            // Gabungkan array kode voucher menjadi teks koma, misal: "007, 008"
            $voucherString = !empty($voucherCodes) ? implode(', ', $voucherCodes) : null;

            $order = Order::create([
                'voucher_code' => $voucherString,
                'total_items' => count($cart),
                'order_type' => $orderType
            ]);

            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty']
                ]);
            }

            // Update status voucher yang digunakan menjadi aktif (terpakai)
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