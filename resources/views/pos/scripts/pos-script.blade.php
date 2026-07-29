<script>

// 1. Parameter 'serverVouchers' untuk menerima data voucher aktif dari database Laravel
function posApp(serverVouchers = []){

    return {

        selectedCategory: 'all',

        categories: @json($categories),

        cart: [],

        // 2. Variabel State untuk Urusan Modal Konfirmasi & Validasi Voucher
        vouchersList: serverVouchers, 
        openModal: false,             
        voucherCode: '',              
        appliedVoucher: null,         
        
        // 3. Variabel State Baru untuk Urusan Modal Riwayat Transaksi
        openHistoryModal: false,
        ordersHistory: [],

        // 4. Fungsi AJAX API untuk Menarik Riwayat Transaksi Terbaru dari Database
        loadHistory() {
            this.openHistoryModal = true;
            fetch('{{ route('pos.history') }}')
                .then(response => response.json())
                .then(data => {
                    this.ordersHistory = data;
                })
                .catch(error => {
                    console.error('Gagal memuat riwayat transaksi:', error);
                });
        },

        selectedCategoryName(){

            if(this.selectedCategory === 'all'){
                return 'Semua Menu'
            }

            let category = this.categories.find(
                c => c.id == this.selectedCategory
            )

            return category ? category.name : ''

        },

        addToCart(product){

            let existing = this.cart.find(i => i.id === product.id)

            if(existing){

                existing.qty++

            }else{

                this.cart.push({
                    ...product,
                    qty: 1
                })

            }

        },

        increase(id){

            let item = this.cart.find(i => i.id === id)

            item.qty++

        },

        decrease(id){

            let item = this.cart.find(i => i.id === id)

            if(item.qty > 1){

                item.qty--

            }else{

                this.cart = this.cart.filter(i => i.id !== id)

            }

        },

        total(){

            return this.cart.reduce((a,b) => {

                return a + (b.price * b.qty)

            }, 0)

        },

        format(number){

            return new Intl.NumberFormat('id-ID').format(number)

        }

    }

}

</script>