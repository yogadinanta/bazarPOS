@extends('admin.layout')

@section('content')

<h1 class="text-4xl font-bold mb-10">
    Edit Kategori
</h1>

<form
    action="/admin/categories/{{ $category->id }}"
    method="POST"
    enctype="multipart/form-data"
    class="bg-white p-10 rounded-2xl"
>

    @csrf
    @method('PUT')

    {{-- NAMA KATEGORI --}}
    <div class="mb-6">

        <label class="block mb-2">
            Nama Kategori
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $category->name) }}"
            class="w-full border h-14 rounded-xl px-5"
            required
        >

    </div>

    {{-- ICON --}}
    <div class="mb-6">

        <label class="block mb-2">
            Upload Icon
        </label>

        <input
            type="file"
            name="icon"
            class="w-full border rounded-xl p-4"
        >

    </div>

    {{-- PREVIEW --}}
    <div class="mb-8">

        <img
            src="{{ asset('uploads/categories/' . $category->icon) }}"
            class="w-28 h-28 object-contain"
        >

    </div>

    {{-- BUTTON --}}
    <button
        type="submit"
        class="bg-red-500 text-white px-8 py-4 rounded-xl"
    >

        Update

    </button>

</form>

@endsection