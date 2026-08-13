@extends('admin.layout')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Penjualan Tiket OTS
        </h1>
        <p class="text-gray-400 text-sm mt-1">Daftar seluruh pencatatan penjualan tiket On The Spot</p>
    </div>

    {{-- Tombol Kembali ke Form Input & Form Search --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
        <a href="{{ route('admin.ticket.ots') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-2xl text-sm font-bold transition inline-flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-plus"></i> Input Tiket Baru
        </a>

        {{-- TOMBOL EXPORT EXCEL --}}
        <a href="{{ route('admin.ticket.export', ['search' => request('search')]) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-2xl text-sm font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>

        <form action="{{ route('admin.ticket.history') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nomor tiket atau metode..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 shadow-xs"
                >
            </div>
            @if(request('search'))
                <a href="{{ route('admin.ticket.history') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-2xl text-sm font-bold transition">
                    Reset
                </a>
            @else
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-xs transition">
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
                <th class="p-5 font-bold"># ID</th>
                <th class="p-5 font-bold">Nomor Tiket / Kupon</th>
                <th class="p-5 font-bold">Metode Pembayaran</th>
                <th class="p-5 font-bold">Waktu Penjualan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
            @forelse($tickets as $ticket)
            <tr class="hover:bg-gray-50/60 transition">
                <td class="p-5 font-bold text-gray-500">
                    {{ $ticket->id }}
                </td>
                <td class="p-5">
                    <span class="bg-green-100 text-green-700 font-extrabold px-3 py-1 rounded-md text-xs">
                        {{ strtoupper($ticket->voucher_code) }}
                    </span>
                </td>
                <td class="p-5">
                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                        {{ $ticket->payment_method == 'cash' ? 'bg-emerald-100 text-emerald-700' : ($ticket->payment_method == 'qris' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ strtoupper($ticket->payment_method) }}
                    </span>
                </td>
                <td class="p-5 text-gray-500">
                    {{ $ticket->created_at->format('d-m-Y H:i:s') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-12 text-gray-400 italic">
                    Belum ada riwayat penjualan tiket OTS yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if($tickets->hasPages())
    <div class="p-5 border-t border-gray-100">
        {{ $tickets->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection