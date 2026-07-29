<div class="p-5" x-data="{ 
    toast: { show: false, message: '', type: 'success' },
    showToast(msg, type = 'success') {
        this.toast.message = msg;
        this.toast.type = type;
        this.toast.show = true;
        setTimeout(() => { this.toast.show = false; }, 3000);
    },
    appliedVouchers: [] // Diubah menjadi array untuk menampung banyak voucher
}">

    <div class="bg-red-500 rounded-[30px] p-5 flex items-center justify-between">
        <div>
            <p class="text-red-200 text-xl">
                <span x-text="cart.length"></span> items
                {{-- Indikator badge jika ada voucher yang aktif --}}
                <template x-if="appliedVouchers.length > 0">
                    <span class="ml-2 text-xs bg-white text-red-500 px-2 py-0.5 rounded-full font-bold" x-text="appliedVouchers.length + ' Voucher Aktif'"></span>
                </template>
            </p>
            <h3 class="text-white text-3xl font-bold">
                Rp <span x-text="format(total())"></span>
            </h3>
        </div>
        <div class="flex items-center gap-4">
            <button
                type="button"
                @click="openModal = true"
                class="bg-white text-red-500 px-10 h-20 rounded-full text-3xl font-bold active:scale-95 transition-transform"
            >
                Order
            </button>
        </div>
    </div>

    {{-- MODAL KONFIRMASI --}}
    <div 
        x-show="openModal" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
        style="display: none;"
        @keydown.escape.window="openModal = false"
    >
        <div 
            @click.away="openModal = false"
            class="bg-white rounded-[30px] p-8 w-full max-w-xl shadow-2xl"
        >
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Konfirmasi Pesanan</h3>
                <p class="text-gray-500 mt-1">Silakan cek pesanan dan masukkan voucher jika ada</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100">
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Total Item:</span>
                    <span class="font-bold text-gray-800" x-text="cart.length + ' Item'"></span>
                </div>
                
                {{-- List Voucher yang Terpasang --}}
                <template x-if="appliedVouchers.length > 0">
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-green-600 block mb-1">Voucher Terpasang:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="(vCode, index) in appliedVouchers" :key="index">
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                    <span x-text="vCode"></span>
                                    <button type="button" @click="appliedVouchers.splice(index, 1)" class="hover:text-red-600 ml-1 font-bold">&times;</button>
                                </span>
                            </template>
                        </div>
                    </div>
                </template>

                <div class="flex justify-between items-center pt-2 border-t border-dashed">
                    <span class="text-lg font-semibold text-gray-700">Total Pembayaran:</span>
                    <span class="text-2xl font-bold text-red-500">Rp <span x-text="format(total())"></span></span>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Kode Voucher</label>
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        x-model="voucherCode"
                        placeholder="Masukkan kode voucher..." 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-base uppercase font-medium tracking-wider"
                    >
                    <button 
                        type="button"
                        @click="
                            if(!voucherCode) { showToast('Masukkan kode voucher!', 'warning'); return; }
                            let cleanCode = voucherCode.trim().toUpperCase();
                            
                            // Cek apakah voucher sudah pernah dimasukkan sebelumnya
                            if(appliedVouchers.includes(cleanCode)) {
                                showToast('Voucher sudah ditambahkan sebelumnya!', 'warning');
                                return;
                            }

                            let found = vouchersList.find(v => v.code.toUpperCase() === cleanCode);
                            if(found) {
                                appliedVouchers.push(found.code); // Tambahkan ke dalam array
                                voucherCode = ''; // Reset input
                                showToast('Voucher berhasil dipasang!', 'success');
                            } else {
                                showToast('Maaf, voucher tidak valid atau hangus!', 'error');
                            }
                        "
                        class="bg-gray-800 text-white px-5 rounded-xl font-bold hover:bg-gray-700 text-sm transition"
                    >
                        Cek
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="button" @click="openModal = false" class="py-3.5 border-2 border-gray-200 text-gray-500 font-bold rounded-xl text-lg">
                    Kembali
                </button>
                
                <button 
                    type="button"
                    @click="
                        if(cart.length === 0) { showToast('Keranjang masih kosong!', 'warning'); return; }
                        
                        // Kirim array voucher ke backend (ubah key sesuai kebutuhan controller Anda, misal: voucher_codes)
                        fetch('{{ route('pos.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ cart: cart, voucher_codes: appliedVouchers })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                let urlNota = '/admin/pos/nota/' + data.order_id;
                                window.open(urlNota, '_blank');

                                showToast('Transaksi Berhasil Disimpan!', 'success');

                                // Hapus voucher yang terpakai dari daftar list lokal
                                if (appliedVouchers.length > 0) {
                                    vouchersList = vouchersList.filter(v => !appliedVouchers.includes(v.code.toUpperCase()));
                                }

                                // Reset UI POS utama
                                cart = [];
                                appliedVouchers = [];
                                voucherCode = '';
                                openModal = false;
                            } else {
                                showToast('Gagal: ' + data.message, 'error');
                            }
                        })
                        .catch(error => { showToast('Terjadi kesalahan sistem database!', 'error'); });
                    "
                    class="py-3.5 bg-red-500 text-white font-bold rounded-xl text-lg hover:bg-red-600 shadow-lg active:scale-95"
                >
                    Selesai & Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFICATION (Sama seperti sebelumnya) --}}
    <div 
        class="fixed top-5 right-5 z-[9999] space-y-2 pointer-events-none"
        x-show="toast.show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-[-20px] scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        style="display: none;"
    >
        <div 
            class="flex items-center gap-3 px-6 py-4 rounded-2xl shadow-xl border text-white font-semibold text-lg max-w-sm pointer-events-auto"
            :class="{
                'bg-emerald-500 border-emerald-600': toast.type === 'success',
                'bg-rose-500 border-rose-600': toast.type === 'error',
                'bg-amber-500 border-amber-600': toast.type === 'warning'
            }"
        >
            <span x-text="toast.message"></span>
        </div>
    </div>
</div>