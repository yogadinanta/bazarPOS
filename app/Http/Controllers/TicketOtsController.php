<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TicketOts;

class TicketOtsController extends Controller
{
    public function index()
    {
        return view('admin.ticket-ots.index');
    }

    public function store(Request $request)
    {
        Log::info('Data Request Tiket OTS masuk:', $request->all());

        $request->validate([
            'voucher_code' => 'required|string',
            'payment_method' => 'required|string|in:cash,qris,transfer'
        ]);

        $code = strtoupper(trim($request->input('voucher_code')));
        $paymentMethod = $request->input('payment_method');

        DB::beginTransaction();
        try {
            // Cukup cek apakah nomor kupon tersebut terdaftar di database (tanpa blokir status is_used)
            $voucher = DB::table('vouchers')->where('code', $code)->first();

            if (!$voucher) {
                Log::warning("Voucher {$code} tidak ditemukan di database.");
                return back()->with('error', "Nomor Tiket/Kupon {$code} tidak terdaftar di database!");
            }

            // SIMPAN DATA PENJUALAN OTS SAJA (Status voucher dibiarkan apa adanya)
            $ticket = TicketOts::create([
                'voucher_code' => $code,
                'payment_method' => $paymentMethod
            ]);
            Log::info('Berhasil mendata penjualan ticket_ots:', $ticket->toArray());

            // Bagian update 'is_used' => true sudah DIHAPUS di sini

            DB::commit();

            return redirect()->route('admin.ticket.ots')->with('success', "Penjualan Tiket OTS #{$code} berhasil dicatat!");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat mencatat Ticket OTS: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    // Menampilkan halaman riwayat penjualan tiket OTS
    public function history(Request $request)
    {
        $search = $request->input('search');

        $tickets = TicketOts::when($search, function ($query, $search) {
                $query->where('voucher_code', 'like', "%{$search}%")
                      ->orWhere('payment_method', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.ticket-ots.history', compact('tickets'));
    }

    public function exportHistory(Request $request)
    {
        $search = $request->input('search');

        $tickets = TicketOts::when($search, function ($query, $search) {
                $query->where('voucher_code', 'like', "%{$search}%")
                      ->orWhere('payment_method', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'riwayat-tiket-ots-' . date('Y-m-d_H-i-s') . '.xls';

        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Desain HTML Excel yang Bersih, Jelas, dan Berwarna (Zebra Striping)
        $html = '
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; }
                .header-title { font-size: 18px; font-weight: bold; color: #1f2937; text-align: center; padding-bottom: 5px; }
                .header-subtitle { font-size: 12px; color: #4b5563; text-align: center; padding-bottom: 3px; }
                .header-date { font-size: 11px; color: #6b7280; text-align: center; padding-bottom: 15px; }
                
                table { border-collapse: collapse; width: 100%; margin-top: 10px; }
                
                th { 
                    background-color: #ef4444; 
                    color: #ffffff; 
                    border: 1px solid #d1d5db; 
                    font-weight: bold; 
                    text-align: center; 
                    padding: 10px 8px;
                    font-size: 12px;
                }
                
                td { 
                    border: 1px solid #e5e7eb; 
                    padding: 8px; 
                    vertical-align: middle; 
                    font-size: 11px;
                    color: #374151;
                }
                
                /* Efek Warna Selang-seling (Zebra Striping) */
                .row-even { background-color: #f9fafb; }
                .row-odd { background-color: #ffffff; }
                
                .text-center { text-align: center; }
                .font-bold { font-weight: bold; }
            </style>
        </head>
        <body>
            <table>
                <tr>
                    <td colspan="4" class="header-title" style="border: none;">BAZAR VOL.1</td>
                </tr>
                <tr>
                    <td colspan="4" class="header-subtitle" style="border: none;">Laporan Penjualan Tiket On The Spot (OTS)</td>
                </tr>
                <tr>
                    <td colspan="4" class="header-date" style="border: none;">Dicetak pada: ' . date('d-m-Y H:i:s') . '</td>
                </tr>
                <tr><td colspan="4" style="border: none; height: 10px;"></td></tr>
                <tr>
                    <th># ID</th>
                    <th>Nomor Tiket / Kupon</th>
                    <th>Metode Pembayaran</th>
                    <th>Waktu Penjualan</th>
                </tr>';

        $no = 1;
        foreach ($tickets as $ticket) {
            // Menentukan warna selang-seling baris (ganjil / genap)
            $rowClass = ($no % 2 == 0) ? 'row-even' : 'row-odd';

            $html .= '<tr class="' . $rowClass . '">
                <td class="text-center font-bold">' . $ticket->id . '</td>
                <td class="text-center font-bold" style="color: #047857;">#' . strtoupper($ticket->voucher_code) . '</td>
                <td class="text-center" style="text-transform: uppercase;">' . $ticket->payment_method . '</td>
                <td class="text-center">' . $ticket->created_at->format('d-m-Y H:i:s') . '</td>
            </tr>';
            $no++;
        }

        $html .= '</table></body></html>';

        return response($html, 200, $headers);
    }
}