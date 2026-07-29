<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bazar POS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
</head>

<body class="bg-gray-100">

<div class="flex">

    {{-- SIDEBAR --}}
    <div class="w-64 min-h-screen bg-red-500 text-white p-6">

        <h1 class="text-3xl font-bold mb-10">
            Bazar Admin
        </h1>

        <div class="space-y-3">

            <a href="{{ url('/admin/categories') }}"
               class="block bg-white/20 hover:bg-white/30 px-5 py-4 rounded-xl transition">

                <i class="fa-solid fa-layer-group mr-2"></i>
                Kategori
            </a>

            <a href="{{ url('/admin/products') }}"
               class="block bg-white/20 hover:bg-white/30 px-5 py-4 rounded-xl transition">

                <i class="fa-solid fa-burger mr-2"></i>
                Produk
            </a>

            <a href="{{ url('/admin/vouchers') }}"
               class="block bg-white/20 hover:bg-white/30 px-5 py-4 rounded-xl transition">

                <i class="fa-solid fa-ticket mr-2"></i>
                Voucher
            </a>

        </div>

    </div>

    {{-- CONTENT --}}
    <div class="flex-1 p-10">

        {{-- INI YANG BIKIN HALAMAN TIDAK PINDAH --}}
        @yield('content')

    </div>

</div>

</body>
</html>