@extends('admin.layout')

@section('content')

<div class="flex items-center justify-between mb-10">

    <h1 class="text-4xl font-bold">
        Data Kategori
    </h1>

    <a href="/admin/categories/create"
        class="bg-red-500 text-white px-6 py-3 rounded-xl">

        Tambah

    </a>

</div>

<div class="bg-white rounded-2xl overflow-hidden">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="p-5 text-left">Icon</th>
                <th class="p-5 text-left">Nama</th>
                <th class="p-5 text-left">Action</th>

            </tr>

        </thead>

        <tbody>

            @foreach($categories as $category)

            <tr class="border-t">

                <td class="p-5">

                    <img
                        src="{{ asset('uploads/categories/' . $category->icon) }}"
                        class="w-16"
                    >

                </td>

                <td class="p-5">

                    {{ $category->name }}

                </td>

                <td class="p-5 flex gap-3">

                    <a href="/admin/categories/{{ $category->id }}/edit"
                        class="bg-yellow-400 px-4 py-2 rounded-lg">

                        Edit

                    </a>

                    <form
                        action="/admin/categories/{{ $category->id }}"
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