@extends('layouts.app')

@section('content')

<div
    x-data="posApp({{ $vouchers->toJson() }})"
    class="w-full h-screen flex"
>

    {{-- LEFT --}}
    <div class="flex-1 flex flex-col">

        @include('pos.partials.header')

        <div class="flex flex-1 overflow-hidden">

            @include('pos.partials.sidebar')

            @include('pos.partials.product-list')

        </div>

    </div>

    {{-- RIGHT --}}
    @include('pos.partials.order-panel')

</div>

@include('pos.scripts.pos-script')

@endsection