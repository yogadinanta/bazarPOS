<div class="h-20 bg-white border-b flex items-center justify-between px-8">

    {{-- LOGO --}}
    <div class="flex items-center gap-3">
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">
            BAZAR
        </h1>
    </div>

    {{-- SEARCH --}}
    <div class="w-[380px]">
        <form method="GET">
            <div class="bg-gray-50 border border-gray-200 rounded-full px-5 h-11 flex items-center shadow-inner">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari menu favorit..."
                    class="w-full outline-none text-base bg-transparent text-gray-700 placeholder-gray-400"
                >
                <button type="submit" class="focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass text-base text-gray-400"></i>
                </button>
            </div>
        </form>
    </div>

</div>