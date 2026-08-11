<div class="flex-1 px-6 py-6 overflow-y-auto hide-scrollbar bg-gray-50/50">

    {{-- TITLE --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-extrabold text-gray-800">
            <span x-text="selectedCategoryName()"></span>
        </h2>
    </div>

    {{-- PRODUCT LIST --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">

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
                class="bg-white border border-gray-100 hover:border-red-400 hover:shadow-sm transition-all rounded-2xl p-5 cursor-pointer flex flex-col justify-between"
            >
                <div>
                    {{-- IMAGE --}}
                    <div class="w-full aspect-square bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden mb-3">
                        <img
                            src="{{ asset('uploads/products/' . $product->image) }}"
                            class="w-32 h-32 object-contain transition duration-300 hover:scale-105"
                        >
                    </div>

                    {{-- NAME --}}
                    <h3 class="text-base font-bold text-gray-800 line-clamp-2 min-h-[48px] leading-snug">
                        {{ $product->name }}
                    </h3>
                </div>

                {{-- PRICE --}}
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-base font-extrabold text-red-500">
                        Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                    </span>
                    <span class="w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </span>
                </div>
            </div>
        </template>
        @endforeach

    </div>

</div>