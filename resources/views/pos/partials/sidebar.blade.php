<div class="w-56 p-5 overflow-y-auto hide-scrollbar">

    <div class="bg-white rounded-[40px] py-6 flex flex-col items-center gap-8 shadow-sm">

        {{-- ALL MENU --}}
        <button
            @click="selectedCategory = 'all'"
            :class="selectedCategory === 'all'
                ? 'bg-red-100 scale-105'
                : ''"
            class="w-full py-4 rounded-3xl text-center transition duration-300"
        >

            <div class="w-24 h-24 mx-auto mb-3 flex items-center justify-center">

                <i class="fa-solid fa-utensils text-5xl text-red-500"></i>

            </div>

            <h3 class="text-2xl font-bold">
                Semua
            </h3>

        </button>

        {{-- CATEGORY --}}
        @foreach($categories as $category)

        <button
            @click="selectedCategory = {{ $category->id }}"
            :class="selectedCategory === {{ $category->id }}
                ? 'bg-red-100 scale-105'
                : ''"
            class="w-full py-4 rounded-3xl text-center hover:scale-105 transition duration-300"
        >

            <div class="w-24 h-24 mx-auto mb-3">

                <img
                    src="{{ asset('uploads/categories/' . $category->icon) }}"
                    class="w-full h-full object-contain"
                >

            </div>

            <h3 class="text-2xl font-medium leading-tight">
                {{ $category->name }}
            </h3>

        </button>

        @endforeach

    </div>

</div>