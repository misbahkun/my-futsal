<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .invoice-header {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            overflow: hidden;
            /* Clear floats */
        }

        .invoice-header-left {
            float: left;
            width: 50%;
        }

        .invoice-header-right {
            float: right;
            width: 50%;
            text-align: right;
        }

        .customer-merchant {
            overflow: hidden;
            /* Clear floats */
        }

        .customer {
            float: left;
            width: 50%;
        }

        .merchant {
            float: right;
            width: 50%;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        table td:last-child,
        table th:last-child {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .notes-terms {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .notes,
        .terms {
            margin-bottom: 20px;
        }

        .paid-stamp {
            padding: 5px 10px;
            background-color: #e6f5ee;
            color: #0c6e39;
            font-weight: bold;
            display: inline-block;
            border-radius: 3px;
        }

        .role {
            padding: 5px 10px;
            background-color: rgb(219 234 254 / var(--tw-bg-opacity, 1)) color: #0c6e39;
            color: rgb(30 64 175 / var(--tw-text-opacity, 1)) font-weight: bold;
            display: inline-block;
            border-radius: 3px;
            margin-bottom: 0px !important;
            margin-top: 0px !important;
        }

        /* Clear floats */
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>MyFutsal</h1>
            <p>Invoice Booking Lapangan</p>
        </div>

        <div class="invoice-header clearfix">
            <div class="invoice-header-left">
                <h2>INVOICE</h2>
                <p><strong>No. Invoice:</strong>
                    INV-{{ $transaction->id }}-{{ date('Ymd', strtotime($transaction->created_at)) }}</p>
                <p><strong>Tanggal:</strong> {{ date('d F Y', strtotime($transaction->updated_at)) }}</p>
            </div>
            <div class="invoice-header-right">
                <div class="paid-stamp">LUNAS</div>
                <p><strong>Metode Pembayaran:</strong> {{ ucwords($transaction->payment_method) }}</p>
            </div>
        </div>

        <div class="customer-merchant clearfix">
            <div class="customer">
                <h3>Informasi Penyewa</h3>
                <p>{{ $transaction->user->full_name }}</p>
                <p>{{ $transaction->user->email }}</p>
                <p>{{ $transaction->user->phone_number }}</p>
                <p class="role">{{ $transaction->user->role }}</p>
            </div>

            <div class="merchant">
                <h3>Informasi Venue</h3>
                <p>MyFutsal</p>
                <p>contact@myfutsal.com</p>
                <p>+62 812-8655-8895</p>
            </div>
        </div>

        <h3>Detail Booking</h3>

        <table>
            <thead>
                <tr>
                    <th>Lapangan</th>
                    <th>Venue</th>
                    <th>Tanggal & Jam</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->field->name }}</td>
                        <td>{{ $schedule->field->venue->name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                            <span style="color: #666; font-size: 10px;">{{ $schedule->time_slots }}</span>
                        </td>
                        <td>Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                {{-- <tr>
                    <td colspan="3" style="text-align: right;"><strong>Subtotal</strong></td>
                    <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr> --}}
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                    <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="notes-terms">
            <div class="notes">
                <h3>Catatan</h3>
                <p>
                    Invoice ini adalah bukti resmi pembayaran. Mohon untuk membawa bukti invoice saat akan
                    bermain. Tunjukkan invoice ini kepada petugas di venue.
                </p>
            </div>
        </div>

        <div class="footer">
            <h3>Terima Kasih Atas Pembayaran Anda!</h3>
            <p>Jika Anda memiliki pertanyaan, silakan hubungi kami melalui WhatsApp di +62 812-8655-8895</p>
            <p>&copy; {{ date('Y') }} MyFutsal. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
