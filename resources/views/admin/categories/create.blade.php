@extends('admin.layout')

@section('content')

<h1 class="text-4xl font-bold mb-10">
    Tambah Kategori
</h1>

<form
    action="/admin/categories"
    method="POST"
    class="bg-white p-10 rounded-2xl"
>

    @csrf

    <div class="mb-5">

        <label class="block mb-2">
            Nama Kategori
        </label>

        <input
            type="text"
            name="name"
            class="w-full border h-14 rounded-xl px-5"
        >

    </div>

    <div class="mb-5">

        <label class="block mb-2">
            Nama Icon
        </label>

        <input
            type="text"
            name="icon"
            placeholder="contoh : drink.png"
            class="w-full border h-14 rounded-xl px-5"
        >

    </div>

    <button class="bg-red-500 text-white px-8 py-4 rounded-xl">

        Simpan

    </button>

</form>

@endsection