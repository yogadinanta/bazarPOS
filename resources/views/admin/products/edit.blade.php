@extends('layouts.app')

@section('content')

<div class="p-10">

    <div class="flex items-center justify-between mb-10">

        <div>
            <h1 class="text-4xl font-black">
                Edit Product
            </h1>

            <p class="text-gray-500 mt-2">
                Update data product
            </p>
        </div>

        <a
            href="{{ route('admin.products.index') }}"
            class="bg-gray-200 px-5 py-3 rounded-xl font-semibold"
        >
            Kembali
        </a>

    </div>

    <div class="bg-white rounded-3xl p-8 shadow">

        <form
            action="{{ route('admin.products.update', $product->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>

                <label class="block text-lg font-semibold mb-2">
                    Nama Product
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ $product->name }}"
                    class="w-full border rounded-2xl px-5 h-14"
                    required
                >

            </div>

            {{-- CATEGORY --}}
            <div>

                <label class="block text-lg font-semibold mb-2">
                    Category
                </label>

                <select
                    name="category_id"
                    class="w-full border rounded-2xl px-5 h-14"
                    required
                >

                    @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        {{ $product->category_id == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>

                    @endforeach

                </select>

            </div>

            {{-- PRICE --}}
            <div>

                <label class="block text-lg font-semibold mb-2">
                    Harga Asli
                </label>

                <input
                    type="number"
                    name="price"
                    value="{{ $product->price }}"
                    class="w-full border rounded-2xl px-5 h-14"
                    required
                >

            </div>

            {{-- DISCOUNT --}}
            <div>

                <label class="block text-lg font-semibold mb-2">
                    Harga Promo
                </label>

                <input
                    type="number"
                    name="discount_price"
                    value="{{ $product->discount_price }}"
                    class="w-full border rounded-2xl px-5 h-14"
                    required
                >

            </div>

            {{-- IMAGE --}}
            <div>

                <label class="block text-lg font-semibold mb-2">
                    Gambar Product
                </label>

                <input
                    type="file"
                    name="image"
                    class="w-full border rounded-2xl p-3"
                >

            </div>

            {{-- CURRENT IMAGE --}}
            <div>

                <p class="font-semibold mb-3">
                    Gambar Saat Ini
                </p>

                <img
                    src="{{ asset('uploads/products/' . $product->image) }}"
                    class="w-40 h-40 object-cover rounded-2xl border"
                >

            </div>

            {{-- BUTTON --}}
            <button
                type="submit"
                class="bg-red-500 text-white px-8 h-14 rounded-2xl font-bold"
            >
                Update Product
            </button>

        </form>

    </div>

</div>

@endsection