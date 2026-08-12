
<script>
function rentalForm() {
    return {

        // ========================
        // STATE
        // ========================
        tanggal_rental: '',
        tanggal_kembali: '',
        hari: 1,

        isSponsor: false,
        isHmjti: false,

        items: [
            {
                barang_id: '',
                jumlah: 1,
                harga: 0,
                subtotal: 0
            }
        ],

        total: 0,

        // ========================
        // PROMO
        // ========================
        kodePromo: '',
        promo: null,
        promoMessage: '',
        diskonNominal: 0,

        // ========================
        // APPLY PROMO
        // ========================
        applyPromo() {

            if (!this.kodePromo.trim()) {
                this.promo = null;
                this.diskonNominal = 0;
                this.promoMessage = 'Masukkan kode promo';
                this.calculateTotal();
                return;
            }

            fetch('/promo/cek', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'kode=' + encodeURIComponent(this.kodePromo.trim())
            })
            .then(res => {

                if (!res.ok) {
                    throw new Error('HTTP Error ' + res.status);
                }

                return res.json();
            })
            .then(res => {

                console.log('RESPONSE PROMO:', res);

                // ========================
                // PROMO BERHASIL
                // ========================
                if (res.success === true) {

                    this.promo = res.promo;

                    /*
                     * Database kamu menggunakan kolom:
                     * nominal = Rp66.000
                     *
                     * Jadi langsung gunakan nominal
                     * sebagai potongan rupiah.
                     */
                    this.diskonNominal =
                        parseFloat(this.promo.nominal) || 0;

                    this.promoMessage =
                        res.message || 'Promo berhasil digunakan.';

                    console.log(
                        'DISKON NOMINAL:',
                        this.diskonNominal
                    );

                }

                // ========================
                // PROMO GAGAL
                // ========================
                else {

                    this.promo = null;
                    this.diskonNominal = 0;

                    this.promoMessage =
                        res.message || 'Kode promo tidak valid.';
                }

                // Hitung ulang total
                this.calculateTotal();
            })
            .catch(err => {

                console.error('ERROR PROMO:', err);

                this.promo = null;
                this.diskonNominal = 0;

                this.promoMessage =
                    'Gagal mengecek kode promo.';

                this.calculateTotal();
            });
        },

        // ========================
        // RESET PROMO
        // ========================
        resetPromo() {

            this.kodePromo = '';
            this.promo = null;
            this.diskonNominal = 0;
            this.promoMessage = '';

            this.calculateTotal();
        },

        // ========================
        // HITUNG HARI
        // ========================
        hitungHari() {

            if (
                !this.tanggal_rental ||
                !this.tanggal_kembali
            ) {
                return;
            }

            const t1 = new Date(this.tanggal_rental);
            const t2 = new Date(this.tanggal_kembali);

            const diff =
                (t2 - t1) / (1000 * 3600 * 24);

            this.hari = diff <= 0 ? 1 : diff;

            this.calculateTotal();
        },

        // ========================
        // TAMBAH ITEM
        // ========================
        addItem() {

            this.items.push({
                barang_id: '',
                jumlah: 1,
                harga: 0,
                subtotal: 0
            });

            this.calculateTotal();
        },

        // ========================
        // HAPUS ITEM
        // ========================
        removeItem(index) {

            this.items.splice(index, 1);

            this.calculateTotal();
        },

        // ========================
        // UPDATE HARGA
        // ========================
        updateHarga(event, index) {

            const harga =
                event.target.options[
                    event.target.selectedIndex
                ].dataset.harga || 0;

            this.items[index].harga =
                parseFloat(harga) || 0;

            this.updateSubtotal(index);
        },

        // ========================
        // UPDATE SUBTOTAL
        // ========================
        updateSubtotal(index) {

            // HMJTI GRATIS
            if (this.isHmjti) {

                this.items[index].subtotal = 0;

                this.calculateTotal();

                return;
            }

            let harga =
                parseFloat(this.items[index].harga) || 0;

            let jumlah =
                parseFloat(this.items[index].jumlah) || 0;

            // SPONSOR DISKON 50%
            if (this.isSponsor) {
                harga = harga * 0.5;
            }

            this.items[index].subtotal =
                jumlah *
                harga *
                this.hari;

            this.calculateTotal();
        },

        // ========================
        // HITUNG TOTAL + PROMO
        // ========================
        calculateTotal() {

            // Hitung subtotal semua barang
            let subtotal = this.items.reduce(
                (sum, item) => {

                    return sum +
                        (parseFloat(item.subtotal) || 0);

                },
                0
            );

            // Default tidak ada diskon
            let diskon = 0;

            // ========================
            // PROMO
            // ========================
            if (this.promo) {

                const nominalPromo =
                    parseFloat(this.promo.nominal) || 0;

                /*
                 * Jika ada minimal transaksi
                 */
                const minimalTransaksi =
                    parseFloat(
                        this.promo.minimal_transaksi
                    ) || 0;

                if (minimalTransaksi > 0 &&
                    subtotal < minimalTransaksi) {

                    this.diskonNominal = 0;

                    this.promoMessage =
                        'Minimal transaksi Rp' +
                        minimalTransaksi.toLocaleString('id-ID') +
                        ' belum terpenuhi.';

                } else {

                    /*
                     * Promo nominal.
                     * Contoh:
                     *
                     * subtotal = 198000
                     * nominal  = 66000
                     *
                     * total = 132000
                     */
                    diskon = nominalPromo;

                    // Jangan sampai total menjadi minus
                    diskon = Math.min(
                        diskon,
                        subtotal
                    );

                    this.diskonNominal = diskon;

                    this.promoMessage =
                        'Promo aktif - diskon ' +
                        this.formatRupiah(diskon);
                }

            } else {

                this.diskonNominal = 0;
            }

            // ========================
            // TOTAL AKHIR
            // ========================
            this.total =
                Math.max(
                    0,
                    subtotal - diskon
                );

            console.log('SUBTOTAL:', subtotal);
            console.log('DISKON:', diskon);
            console.log('TOTAL AKHIR:', this.total);
        },

        // ========================
        // TOGGLE SPONSOR
        // ========================
        toggleSponsor() {

            // Kalau sponsor dipilih,
            // HMJTI dimatikan
            if (this.isSponsor) {
                this.isHmjti = false;
            }

            this.items.forEach(
                (item, index) => {
                    this.updateSubtotal(index);
                }
            );
        },

        // ========================
        // TOGGLE HMJTI
        // ========================
        toggleHmjti() {

            // Kalau HMJTI dipilih,
            // sponsor dimatikan
            if (this.isHmjti) {
                this.isSponsor = false;
            }

            this.items.forEach(
                (item, index) => {
                    this.updateSubtotal(index);
                }
            );
        },

        // ========================
        // FORMAT RUPIAH
        // ========================
        formatRupiah(value) {

            return (
                parseFloat(value) || 0
            ).toLocaleString(
                'id-ID',
                {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }
            );
        }
    }
}
</script>
```

Lalu **ubah bagian tampilan diskon** yang sekarang:

```html
<p class="text-right text-sm text-gray-500" x-show="diskonNominal > 0">
    Diskon: <span x-text="diskonNominal * 100"></span>%
</p>
```

menjadi:

```html
<p class="text-right text-sm text-gray-500" x-show="diskonNominal > 0">
    Diskon:
    <span x-text="formatRupiah(diskonNominal)"></span>
</p>
```
