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
        'order_type' => 'required|string|in:dine_in,take_away',
        'table_number' => 'nullable|string|max:50', // <-- Tambahkan validasi ini
        'payment_method' => 'required|string|in:cash,qris,kupon'
    ]);

    $cart = $request->input('cart');
    $paymentMethod = $request->input('payment_method');
    $voucherCodes = $paymentMethod === 'kupon' ? $request->input('voucher_codes', []) : [];
    $orderType = $request->input('order_type');
    
    // Ambil nomor meja hanya jika tipe pesanannya dine_in
    $tableNumber = $orderType === 'dine_in' ? $request->input('table_number') : null;

    DB::beginTransaction();
    try {
        if ($paymentMethod === 'kupon' && empty($voucherCodes)) {
            return response()->json(['success' => false, 'message' => 'Metode pembayaran Kupon wajib menyertakan nomor kupon!'], 422);
        }
        
        if (!empty($voucherCodes)) {
            foreach ($voucherCodes as $code) {
                $checkVoucher = DB::table('vouchers')->where('code', $code)->where('is_used', false)->first();
                if (!$checkVoucher) {
                    return response()->json(['success' => false, 'message' => "Voucher {$code} sudah hangus atau tidak valid!"], 422);
                }
            }
        }

        $voucherString = !empty($voucherCodes) ? implode(', ', $voucherCodes) : null;

        // Simpan data order beserta nomor meja
        $order = Order::create([
            'voucher_code' => $voucherString,
            'total_items' => count($cart),
            'order_type' => $orderType,
            'table_number' => $tableNumber, // <-- Tambahkan ini
            'payment_method' => $paymentMethod
        ]);

        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'qty' => $item['qty']
            ]);
        }

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
    public function generateMoreVouchers()
{
    // Loop dari 801 sampai 1000
    for ($i = 801; $i <= 1000; $i++) {
        // Format angka menjadi 3 digit (contoh: 0801, 0910, 1000)
        $code = str_pad($i, 4, '0', STR_PAD_LEFT);

        // Cek apakah kode sudah ada di database agar tidak duplikat
        $exists = DB::table('vouchers')->where('code', $code)->exists();

        if (!$exists) {
            DB::table('vouchers')->insert([
                'code' => $code,
                'is_used' => 0,
                'used_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    return "Berhasil menambahkan voucher dari 0801 sampai 1000!";
}

public function exportHistory(Request $request)
{
    $search = $request->input('search');

    $orders = Order::with('details')
        ->when($search, function ($query, $search) {
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('voucher_code', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $filename = 'riwayat-transaksi-' . date('Y-m-d_H-i-s') . '.xls';

    $headers = [
        "Content-type"        => "application/vnd.ms-excel",
        "Content-Disposition" => "attachment; filename={$filename}",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $html = '
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <style>
            .title { font-size: 16px; font-weight: bold; }
            .subtitle { font-size: 12px; color: #555; }
            table { border-collapse: collapse; width: 100%; margin-top: 10px; }
            th { background-color: #f2f2f2; border: 1px solid #000; font-weight: bold; text-align: center; padding: 8px; }
            td { border: 1px solid #000; padding: 6px; vertical-align: middle; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <td colspan="7" class="title" style="border: none;">BAZAR VOL.1</td>
            </tr>
            <tr>
                <td colspan="7" class="subtitle" style="border: none;">Laporan Riwayat Transaksi Penjualan</td>
            </tr>
            <tr>
                <td colspan="7" class="subtitle" style="border: none;">Dicetak pada: ' . date('d-m-Y H:i:s') . '</td>
            </tr>
            <tr><td colspan="7" style="border: none;"></td></tr>
            <tr>
                <th>ID Resi</th>
                <th>Waktu Transaksi</th>
                <th>Tipe Pesanan</th>
                <th>Metode Pembayaran</th>
                <th>Voucher Digunakan</th>
                <th>Detail Item (Menu x Qty)</th>
                <th>Total Jenis Item</th>
            </tr>';

    foreach ($orders as $order) {
        $itemsDetail = $order->details->map(function($detail) {
            return $detail->product_name . ' (' . $detail->qty . 'x)';
        })->implode(', ');

        $html .= '<tr>
            <td class="text-center">#' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '</td>
            <td>' . $order->created_at->format('d-m-Y H:i:s') . '</td>
            <td class="text-center">' . strtoupper($order->order_type) . '</td>
            <td class="text-center">' . strtoupper($order->payment_method) . '</td>
            <td>' . ($order->voucher_code ?? '-') . '</td>
            <td>' . htmlspecialchars($itemsDetail) . '</td>
            <td class="text-center">' . $order->total_items . '</td>
        </tr>';
    }

    $html .= '</table></body></html>';

    return response($html, 200, $headers);
}
}