<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bazar POS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
</head>

<body class="bg-gray-50 text-gray-800 antialiased font-sans">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <div class="w-72 bg-red-500 text-white p-6 flex flex-col justify-between shrink-0 shadow-lg">
        <div>
            <div class="flex items-center gap-3 mb-10 px-2">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center font-bold text-xl shadow-xs">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-wide">
                    Bazar Admin
                </h1>
            </div>

            <div class="space-y-2">
                <a href="{{ url('/admin/categories') }}"
                   class="flex items-center gap-3 px-5 py-3.5 rounded-2xl font-semibold transition {{ request()->is('admin/categories*') ? 'bg-white text-red-500 shadow-md' : 'bg-white/10 hover:bg-white/20 text-white' }}">
                    <i class="fa-solid fa-layer-group text-lg w-6"></i>
                    <span>Kategori</span>
                </a>

                <a href="{{ url('/admin/products') }}"
                   class="flex items-center gap-3 px-5 py-3.5 rounded-2xl font-semibold transition {{ request()->is('admin/products*') ? 'bg-white text-red-500 shadow-md' : 'bg-white/10 hover:bg-white/20 text-white' }}">
                    <i class="fa-solid fa-burger text-lg w-6"></i>
                    <span>Produk</span>
                </a>

                <a href="{{ url('/admin/vouchers') }}"
                   class="flex items-center gap-3 px-5 py-3.5 rounded-2xl font-semibold transition {{ request()->is('admin/vouchers*') ? 'bg-white text-red-500 shadow-md' : 'bg-white/10 hover:bg-white/20 text-white' }}">
                    <i class="fa-solid fa-ticket text-lg w-6"></i>
                    <span>Voucher</span>
                </a>

                <a href="{{ url('/admin/history') }}"
                   class="flex items-center gap-3 px-5 py-3.5 rounded-2xl font-semibold transition {{ request()->is('admin/history*') ? 'bg-white text-red-500 shadow-md' : 'bg-white/10 hover:bg-white/20 text-white' }}">
                    <i class="fa-solid fa-history text-lg w-6"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </div>
        </div>

        {{-- Footer Sidebar Kecil (Opsional) --}}
        <div class="px-2 pt-6 border-t border-red-400/50 text-xs text-red-100 flex items-center justify-between">
            <span>Bazar POS v1.0</span>
            <i class="fa-solid fa-shield-halved"></i>
        </div>
    </div>

    {{-- CONTENT WRAPPER --}}
    <div class="flex-1 flex flex-col min-w-0">
        
        {{-- TOPBAR SEDERHANA --}}
        <header class="bg-white border-b border-gray-100 h-20 px-10 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2 text-gray-500 text-sm">
                <i class="fa-solid fa-house"></i>
                <span>/</span>
                <span class="font-semibold text-gray-800 capitalize">{{ request()->segment(2) ?: 'Dashboard' }}</span>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="font-bold text-sm text-gray-800">Administrator</p>
                    <p class="text-xs text-gray-400">Kasir / Admin</p>
                </div>
                <div class="w-10 h-10 bg-red-100 text-red-500 rounded-full flex items-center justify-center font-bold">
                    A
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-10 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>