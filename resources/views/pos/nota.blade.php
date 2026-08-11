<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            width: 58mm;
            margin: 0;
            padding: 2px 6px;
            box-sizing: border-box;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .w-full { width: 100%; }
        .my-1 { margin-top: 4px; margin-bottom: 4px; }
        .my-2 { margin-top: 8px; margin-bottom: 8px; }
        .border-b { border-bottom: 1px dashed #000; }
        .border-b-dotted { border-bottom: 1px dotted #000; }
        .space-y-05 p { margin: 2px 0; }
        .bg-gray { background-color: #f3f4f6; padding: 6px 4px; border: 1px solid #000; }
        .flex-between { display: flex; justify-content: space-between; }
        
        @media print {
            body { width: 58mm; margin: 0; padding: 2px 6px; }
            .bg-gray { background-color: transparent !important; border: 1px dashed #000; }
            @page { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="text-center">
        <h3 style="margin: 0 0 2px 0; font-size: 13px; font-weight: bold; letter-spacing: 1px;">BAZAR POS INDONESIA</h3>
        <p style="margin: 0;">Jl. Raya Ubud, Gianyar, Bali</p>
        <p style="margin: 0;">Telp: 08123456789</p>
    </div>
    
    <div class="border-b my-2"></div>
    
    <div class="space-y-05">
        <div class="flex-between">
            <span>RESI: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
            <span>{{ strtoupper($order->payment_method ?? 'CASH') }}</span>
        </div>
        <div class="flex-between">
            <span>TIPE:</span>
            <span style="font-weight: bold;">{{ isset($order->order_type) ? ($order->order_type == 'dine_in' ? 'DINE IN' : 'TAKE AWAY') : 'DINE IN' }}</span>
        </div>
        <p>TGL  : <span id="local-time">--/--/---- --:--:--</span></p>
        <p>KASIR: Administrator</p>
    </div>
    
    <div class="border-b my-2"></div>
    
    <table class="w-full text-left" style="table-layout: fixed; border-collapse: collapse;">
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach($order->details as $item)
                @php $subtotal += ($item->price * $item->qty); @endphp
                <tr>
                    <td colspan="2" style="padding-top: 4px; font-weight: bold; word-wrap: break-word;">
                        {{ strtoupper($item->product_name) }}
                    </td>
                </tr>
                <tr class="border-b-dotted">
                    <td style="width: 50%; padding-bottom: 4px; padding-left: 5px;">
                        {{ $item->qty }} X {{ number_format($item->price, 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="width: 50%; padding-bottom: 4px;">
                        {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="border-b my-2"></div>
    
    <div class="space-y-05">
        <div class="flex-between" style="font-size: 12px;">
            <span style="font-weight: bold;">TOTAL BELANJA:</span>
            <span style="font-weight: bold;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    
    <div class="border-b my-2"></div>
    
    <div class="text-center bg-gray my-2">
        <p style="margin: 0; font-size: 10px; letter-spacing: 0.5px;">VOUCHER DIGUNAKAN</p>
        <p style="margin: 4px 0 0 0; font-weight: bold; font-size: 12px; letter-spacing: 1px; word-break: break-all;">
            {{ $order->voucher_code ? strtoupper($order->voucher_code) : '- TIDAK ADA -' }}
        </p>
    </div>

    <div class="border-b my-2"></div>
    
    <div class="text-center" style="margin-top: 12px; font-size: 10px;">
        <p style="margin: 0 0 2px 0; font-weight: bold; font-size: 11px;">--- TERIMA KASIH ---</p>
        <p style="margin: 0;">BARANG YANG SUDAH DIBELI</p>
        <p style="margin: 0;">TIDAK DAPAT DITUKAR/DIKEMBALIKAN</p>
    </div>

    <script>
        window.onload = function() {
            // 1. GENERATE WAKTU LOKAL KLIEN (KOMPUTER KASIR)
            const now = new Date();
            
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            document.getElementById('local-time').innerText = `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;

            // 2. TRIGGER PRINT DAN TUTUP OTOMATIS
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 300);
            }, 200);
        };
    </script>
</body>
</html>