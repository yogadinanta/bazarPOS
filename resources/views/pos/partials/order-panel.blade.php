<div class="w-full max-w-[430px] bg-white border-l flex flex-col h-full shadow-lg">

    {{-- TOP --}}
    <div class="h-auto py-5 px-6 flex items-center justify-between border-b bg-white">
        <div class="flex items-center gap-4 w-full">
            <button 
                type="button"
                @click="loadHistory()" 
                class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center cursor-pointer active:scale-95 transition-transform hover:bg-red-600 shrink-0 shadow-md"
            >
                <i class="fa-solid fa-receipt text-white text-lg"></i>
            </button>

            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold text-gray-800 truncate">
                    Order Menu
                </h2>
                <p class="text-gray-400 text-sm mt-0.5 truncate">
                    Order POS
                </p>
            </div>
        </div>
    </div>

    {{-- CART --}}
    <div class="flex-1 overflow-y-auto p-4 space-y-4">

        <template x-if="cart.length === 0">
            <div class="h-full flex items-center justify-center py-20">
                <p class="text-lg text-gray-400 text-center italic">
                    Silahkan Pilih Menu...
                </p>
            </div>
        </template>

        <template x-for="item in cart" :key="item.id">
            <div class="border-2 border-orange-200 hover:border-orange-300 transition-colors rounded-2xl p-3 flex items-center gap-3 bg-white shadow-sm">
                <img
                    :src="item.image"
                    class="w-20 h-20 object-cover rounded-xl shrink-0 bg-gray-50"
                >

                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-gray-800 truncate" x-text="item.name"></h3>
                    <p class="text-orange-600 font-semibold text-sm mt-1">
                        Rp <span x-text="format(item.price)"></span>
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button
                        type="button"
                        @click="decrease(item.id)"
                        class="w-8 h-8 bg-orange-400 text-white rounded-lg flex items-center justify-center font-bold hover:bg-orange-500 active:scale-95 transition"
                    >
                        -
                    </button>
                    <span class="text-base font-bold w-6 text-center text-gray-700" x-text="item.qty"></span>
                    <button
                        type="button"
                        @click="increase(item.id)"
                        class="w-8 h-8 bg-orange-400 text-white rounded-lg flex items-center justify-center font-bold hover:bg-orange-500 active:scale-95 transition"
                    >
                        +
                    </button>
                </div>
            </div>
        </template>

    </div>

    {{-- FOOTER ORDER --}}
    <div class="p-4 bg-white border-t">
        @include('pos.partials.footer-order')
    </div>

    {{-- HISTORY MODAL --}}
    <div 
        x-show="openHistoryModal" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        style="display: none;"
        @keydown.escape.window="openHistoryModal = false"
    >
        <div 
            @click.away="openHistoryModal = false"
            class="bg-white rounded-3xl p-6 w-full max-w-xl shadow-2xl flex flex-col max-h-[85vh]"
        >
            <div class="flex justify-between items-center pb-4 border-b">
                <h3 class="text-xl font-bold text-gray-800">Riwayat Transaksi Terbaru</h3>
                <button @click="openHistoryModal = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <div class="flex-1 overflow-y-auto my-4 space-y-3 pr-1">
                <template x-if="ordersHistory.length === 0">
                    <p class="text-center text-gray-400 py-8 italic text-sm">Memuat data transaksi...</p>
                </template>

                <template x-for="order in ordersHistory" :key="order.id">
                    <div class="border border-gray-100 rounded-2xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50 hover:bg-gray-100 transition">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-gray-800 text-base" x-text="'Nota #' + String(order.id).padStart(6, '0')"></span>
                                <template x-if="order.voucher_code">
                                    <span class="text-xs bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full" x-text="'Voucher: ' + order.voucher_code"></span>
                                </template>
                            </div>
                            <p class="text-xs text-gray-400 mt-1" x-text="new Date(order.created_at).toLocaleString('id-ID')"></p>
                            
                            <div class="mt-2 text-xs text-gray-600 flex flex-wrap gap-1">
                                <template x-for="detail in order.details">
                                    <span class="bg-white border px-2 py-0.5 rounded-md">
                                        <span x-text="detail.product_name"></span> (<span x-text="detail.qty"></span>x)
                                    </span>
                                </template>
                            </div>
                        </div>

                        <button 
                            type="button"
                            @click="window.open('/admin/pos/nota/' + order.id, '_blank')"
                            class="bg-red-500 text-white font-bold px-3.5 py-2 rounded-xl text-xs hover:bg-red-600 shadow-sm active:scale-95 transition w-full sm:w-auto text-center shrink-0"
                        >
                            Cek & Cetak
                        </button>
                    </div>
                </template>
            </div>

            <div class="pt-3 border-t flex justify-end">
                <button 
                    type="button" 
                    @click="openHistoryModal = false" 
                    class="px-5 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition text-sm"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>