<div class="w-52 p-4 overflow-y-auto hide-scrollbar bg-gray-50 border-r">

    <div class="flex flex-col gap-2.5">

        {{-- ALL MENU --}}
        <button @click="selectedCategory = 'all'"
            :class="selectedCategory === 'all'
                ?
                'bg-red-500 text-white shadow-sm' :
                'bg-white hover:bg-gray-100 text-gray-700'"
            class="w-full py-3 px-4 rounded-xl text-left transition duration-200 flex items-center gap-3.5 border border-gray-100 shadow-sm">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-red-100/10 shrink-0">
                <i class="fa-solid fa-utensils text-lg"
                    :class="selectedCategory === 'all' ? 'text-white' : 'text-red-500'"></i>
            </div>
            <span class="text-base font-semibold">
                Semua
            </span>
        </button>

        {{-- CATEGORY --}}
        @foreach ($categories as $category)
            <button @click="selectedCategory = {{ $category->id }}"
                :class="selectedCategory === {{ $category->id }} ?
                    'bg-red-500 text-white shadow-sm' :
                    'bg-white hover:bg-gray-100 text-gray-700'"
                class="w-full py-3 px-4 rounded-xl text-left transition duration-200 flex items-center gap-3.5 border border-gray-100 shadow-sm">
                <div class="w-9 h-9 rounded-lg overflow-hidden flex items-center justify-center bg-gray-50 shrink-0">
                    <img src="{{ asset('uploads/categories/' . $category->icon) }}" class="w-7 h-7 object-contain">
                </div>
                <span class="text-base font-semibold truncate">
                    {{ $category->name }}
                </span>
            </button>
        @endforeach

    </div>

</div>
