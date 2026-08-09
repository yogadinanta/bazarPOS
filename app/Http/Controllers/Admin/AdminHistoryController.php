<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminHistoryController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

    $orders = Order::with('details')
        ->when($search, function ($query, $search) {
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('voucher_code', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('admin.history.index', compact('orders')); // Ubah ke admin.history.index
}
}