@extends('admin.layout')

@section('content')

{{-- HEADER & CONTROLS SECTION --}}
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Transaksi
        </h1>
        <p class="text-gray-400 text-sm mt-1">Daftar seluruh transaksi penjualan yang masuk secara real-time</p>
    </div>

    {{-- ACTION BUTTONS & SEARCH BAR --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
        {{-- Tombol Export Excel --}}
        <a href="{{ route('admin.history.export', ['search' => request('search')]) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-xs transition inline-flex items-center justify-center gap-2 shrink-0">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>

        {{-- Form Search / Filter Index --}}
        <form action="{{ url('/admin/history') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari ID resi atau voucher..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 shadow-xs"
                >
            </div>
            @if(request('search'))
                <a href="{{ url('/admin/history') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-2xl text-sm font-bold transition shrink-0">
                    Reset
                </a>
            @else
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-xs transition shrink-0">
                    Cari
                </button>
            @endif
        </form>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm uppercase tracking-wider">
            <tr>
                <th class="p-5 font-bold">Resi</th>
                <th class="p-5 font-bold">Waktu Transaksi</th>
                <th class="p-5 font-bold">Tipe Pesanan</th>
                <th class="p-5 font-bold">Voucher Digunakan</th>
                <th class="p-5 font-bold">Detail Item Menu</th>
                <th class="p-5 font-bold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50/60 transition">
                <td class="p-5 font-bold text-gray-800">
                    #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                </td>
                <td class="p-5 text-gray-500">
                    {{ $order->created_at->format('d-m-Y H:i:s') }}
                </td>
                <td class="p-5">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->order_type == 'take_away' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $order->order_type == 'take_away' ? 'Take Away' : 'Dine In' }}
                    </span>
                </td>
                <td class="p-5">
                    @if($order->voucher_code)
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode(',', $order->voucher_code) as $vCode)
                                <span class="bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-md text-xs">
                                    {{ trim($vCode) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-gray-400 italic text-xs">- Tidak Ada -</span>
                    @endif
                </td>
                <td class="p-5">
                    <div class="flex flex-wrap gap-1 max-w-xs">
                        @foreach($order->details as $detail)
                            <span class="bg-gray-100 border px-2 py-0.5 rounded-md text-xs text-gray-600">
                                {{ $detail->product_name }} (<span class="font-bold">{{ $detail->qty }}x</span>)
                            </span>
                        @endforeach
                    </div>
                </td>
                <td class="p-5 text-center">
                    <a href="/admin/pos/nota/{{ $order->id }}" target="_blank"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-xs active:scale-95 transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-print"></i> Cetak
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400 italic">
                    Belum ada riwayat transaksi yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if($orders->hasPages())
    <div class="p-5 border-t border-gray-100">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection