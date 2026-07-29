@extends('admin.layout')

@section('content')

<div class="flex items-center justify-between mb-10">

    <h1 class="text-4xl font-bold">
        Data Produk
    </h1>

    <a href="/admin/products/create"
        class="bg-red-500 text-white px-6 py-3 rounded-xl">

        Tambah Produk

    </a>

</div>

<div class="bg-white rounded-2xl overflow-hidden">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="p-5 text-left">Image</th>
                <th class="p-5 text-left">Nama</th>
                <th class="p-5 text-left">Kategori</th>
                <th class="p-5 text-left">Harga</th>
                <th class="p-5 text-left">Action</th>

            </tr>

        </thead>

        <tbody>

            @foreach($products as $product)

            <tr class="border-t">

                <td class="p-5">

<img
    src="{{ asset('uploads/products/' . $product->image) }}"
    class="w-20 h-20 object-cover rounded-xl"
>

                </td>

                <td class="p-5">

                    {{ $product->name }}

                </td>

                <td class="p-5">

                    {{ $product->category->name }}

                </td>

                <td class="p-5">

                    Rp {{ number_format($product->price) }}

                </td>

                <td class="p-5 flex gap-3">

                    <a href="/admin/products/{{ $product->id }}/edit"
                        class="bg-yellow-400 px-4 py-2 rounded-lg">

                        Edit

                    </a>

                    <form
                        action="/admin/products/{{ $product->id }}"
                        method="POST"
                    >

                        @csrf
                        @method('DELETE')

                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection