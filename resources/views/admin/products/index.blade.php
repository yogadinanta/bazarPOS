@extends('admin.layout')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Data Produk
        </h1>
        <p class="text-gray-400 text-sm mt-1">Kelola daftar menu dan produk makanan/minuman Anda di sini</p>
    </div>

    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
        {{-- Form Pencarian / Filter Produk --}}
        <form action="{{ url('/admin/products') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama produk..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 shadow-xs"
                >
            </div>
            @if(request('search'))
                <a href="{{ url('/admin/products') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-2xl text-sm font-bold transition">
                    Reset
                </a>
            @else
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-xs transition">
                    Cari
                </button>
            @endif
        </form>

        {{-- Tombol Tambah Produk --}}
        <a href="/admin/products/create"
            class="bg-red-500 hover:bg-red-600 text-white font-bold px-5 py-2.5 rounded-2xl shadow-md active:scale-95 transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm uppercase tracking-wider">
            <tr>
                <th class="p-5 font-bold">Image</th>
                <th class="p-5 font-bold">Nama Produk</th>
                <th class="p-5 font-bold">Kategori</th>
                <th class="p-5 font-bold">Harga</th>
                <th class="p-5 font-bold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50/60 transition">
                <td class="p-5">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center p-1 shadow-xs">
                        <img
                            src="{{ asset('uploads/products/' . $product->image) }}"
                            class="w-full h-full object-cover rounded-xl"
                            alt="{{ $product->name }}"
                        >
                    </div>
                </td>
                <td class="p-5 font-bold text-gray-800 text-base">
                    {{ $product->name }}
                </td>
                <td class="p-5">
                    <span class="bg-gray-100 text-gray-600 font-semibold px-3 py-1 rounded-lg text-xs">
                        {{ optional($product->category)->name ?? 'Tanpa Kategori' }}
                    </span>
                </td>
                <td class="p-5 font-semibold text-red-600">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </td>
                <td class="p-5">
                    <div class="flex items-center justify-center gap-2">
                        <a href="/admin/products/{{ $product->id }}/edit"
                            class="bg-amber-400 hover:bg-amber-500 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-xs active:scale-95 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>

                        <form
                            action="/admin/products/{{ $product->id }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus produk ini?');"
                            class="inline-block"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-xs active:scale-95 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-12 text-gray-400 italic">
                    Belum ada data produk yang ditambahkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if(method_exists($products, 'hasPages') && $products->hasPages())
    <div class="p-5 border-t border-gray-100">
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection