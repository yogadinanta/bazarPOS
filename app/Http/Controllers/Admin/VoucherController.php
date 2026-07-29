<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request; // <--- TAMBAHKAN BARIS INI

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $vouchers = Voucher::query()
            ->when($search, function ($query, $search) {
                return $query->where('code', 'like', "%{$search}%");
            })
            ->paginate(10); // <--- Membatasi 10 data per halaman

        return view('admin.vouchers.index', compact('vouchers'));
    }
}