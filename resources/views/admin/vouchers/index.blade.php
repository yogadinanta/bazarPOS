@extends('admin.layout')

@section('content')

<div>

    <h1 class="text-3xl font-bold mb-6">Voucher Tiket</h1>

    <!-- Wrapper untuk Fitur Cari & Tabel -->
    <div class="bg-white p-6 rounded-2xl shadow">
        
        <!-- Fitur Search -->
        <div class="flex justify-end mb-4">
            <form action="{{ request()->url() }}" method="GET" class="w-full max-w-xs">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari kode voucher..." 
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    >
                    @if(request('search'))
                        <a href="{{ request()->url() }}" class="absolute right-12 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                            Clear
                        </a>
                    @endif
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-500 hover:text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b text-gray-600">
                        <th class="pb-3">Kode</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Used At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($vouchers as $voucher)
                    <tr class="border-b last:border-none hover:bg-gray-50 transition">
                        <td class="font-bold py-4">
                            {{ $voucher->code }}
                        </td>

                        <td class="py-4">
                            @if($voucher->is_used)
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">USED</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-600">AVAILABLE</span>
                            @endif
                        </td>

                        <td class="py-4 text-gray-600">
                            {{ $voucher->used_at ? \Carbon\Carbon::parse($voucher->used_at)->format('d M Y H:i') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-400 italic">
                            Data voucher tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $vouchers->appends(['search' => request('search')])->links() }}
        </div>

    </div>

</div>

@endsection