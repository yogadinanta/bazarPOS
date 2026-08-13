@extends('admin.layout')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mt-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Penjualan Tiket OTS</h1>
            <p class="text-gray-400 text-sm mt-1">Input nomor kupon/tiket masuk langsung dari admin</p>
        </div>
        <a href="{{ route('admin.ticket.history') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-2xl text-xs font-bold transition inline-flex items-center gap-1.5">
            <i class="fa-solid fa-clock-rotate-left"></i> Lihat Riwayat
        </a>
    </div>
<div class="max-w-xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mt-6">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-800">Penjualan Tiket OTS</h1>
        <p class="text-gray-400 text-sm mt-1">Input nomor kupon/tiket masuk langsung dari admin</p>
    </div>

    {{-- Notifikasi Pesan Sukses / Error --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.ticket.store') }}" method="POST">
        @csrf

        {{-- Input Nomor Kupon / Tiket --}}
        <div class="mb-5">
            <label class="block text-gray-600 font-semibold mb-2 text-xs uppercase tracking-wider">Nomor Tiket / Kupon</label>
            <input 
                type="text" 
                name="voucher_code" 
                placeholder="Contoh: 001 atau 0850" 
                required
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold uppercase focus:outline-none focus:ring-2 focus:ring-red-500"
            >
            <p class="text-[11px] text-gray-400 mt-1.5">Masukkan nomor kupon fisik yang dibeli pengunjung.</p>
        </div>

        {{-- Pilihan Metode Pembayaran --}}
        <div class="mb-6">
            <label class="block text-gray-600 font-semibold mb-2 text-xs uppercase tracking-wider">Metode Pembayaran</label>
            <div class="grid grid-cols-3 gap-3">
                <label class="flex items-center justify-center p-3 border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition font-bold text-xs text-gray-700">
                    <input type="radio" name="payment_method" value="cash" checked class="mr-2 text-red-500 focus:ring-red-500">
                    Cash
                </label>
                <label class="flex items-center justify-center p-3 border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition font-bold text-xs text-gray-700">
                    <input type="radio" name="payment_method" value="qris" class="mr-2 text-red-500 focus:ring-red-500">
                    QRIS
                </label>
                <label class="flex items-center justify-center p-3 border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition font-bold text-xs text-gray-700">
                    <input type="radio" name="payment_method" value="transfer" class="mr-2 text-red-500 focus:ring-red-500">
                    Transfer
                </label>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" class="w-full py-3.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-2xl text-sm shadow-sm transition active:scale-95">
            Simpan Penjualan Tiket
        </button>
    </form>
</div>

@endsection