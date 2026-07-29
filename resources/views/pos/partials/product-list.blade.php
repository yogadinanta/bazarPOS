<div class="flex-1 px-8 py-8 overflow-y-auto hide-scrollbar">

    {{-- TITLE --}}
    <div class="flex items-center justify-between mb-10">

        <h2 class="text-6xl font-black">

            <span x-show="selectedCategoryName()">
                <span x-text="selectedCategoryName()"></span>
            </span>

            <span x-show="selectedCategory === 'all'">
                Semua Menu
            </span>

        </h2>

    </div>

    {{-- PRODUCT LIST --}}
    <div class="flex gap-10 flex-wrap">

        @foreach($products as $product)

        <template
            x-if="
                selectedCategory === 'all'
                ||
                selectedCategory == {{ $product->category_id }}
            "
        >

            <div
                @click="addToCart({
                    id: {{ $product->id }},
                    name: '{{ $product->name }}',
                    price: {{ $product->discount_price }},
                    image: '{{ asset('uploads/products/' . $product->image) }}'
                })"
                class="w-60 text-center cursor-pointer group"
            >

                <div class="border-2 border-red-200 rounded-[40px] p-5 hover:border-red-500 transition">

                    {{-- IMAGE --}}
                    <img
                        src="{{ asset('uploads/products/' . $product->image) }}"
                        class="w-44 h-44 object-contain mx-auto
                        group-hover:scale-110
                        transition duration-300"
                    >

                    {{-- NAME --}}
                    <h3 class="text-2xl font-bold leading-tight mt-3 min-h-[90px]">

                        {{ $product->name }}

                    </h3>

                    {{-- PRICE --}}
                    <div class="bg-[#ececec] rounded-full py-3 mt-5">

                        <h4 class="text-2xl font-semibold">

                            Rp {{ number_format($product->discount_price, 0, ',', '.') }}

                        </h4>

                    </div>

                </div>

            </div>

        </template>

        @endforeach

    </div>

</div>