<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        h1 {
            margin-bottom: 0;
        }

        .header,
        .footer {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .contact {
            margin-top: 5px;
            font-size: 14px;
        }

        .info {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .info p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }

        .totals p {
            margin: 4px 0;
            text-align: right;
        }

        .thankyou {
            margin-top: 80px;
            font-size: 14px;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- @php
        $company = \App\Models\Company::first();
    @endphp

    <div class="header">
        <h1>{{ $company->name }}</h1>
        <div class="contact">
            {{ $company->address }}<br>
            {{ $company->email }} | {{ $company->contact }}
        </div>
    </div> --}}

    <div class="header">
        <h1>PT LAPI LABORATORIES</h1>
        <div class="contact">
            Jalan Gedong Panjang 32<br>
            ptlapilaboratories@gmail.com | +62 21 6902626
        </div>
    </div>

    <div class="info">
        <p><strong>To:</strong> {{ $order->client->user->name }}</p>
        <p>{{ $order->client->address }}</p>

        <p class="right"><strong>INVOICE NUMBER:</strong> {{ $order->order_number }}</p>
        <p class="right"><strong>INVOICE DATE:</strong> {{ $order->created_at->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderDetails as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->price, 0) }}</td>
                    <td>{{ number_format($detail->subtotal, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $tax = $order->total * 0.05;
        $grandTotal = $order->total + $tax;
    @endphp

    <div class="totals">
        <p>Sub total (excl. Tax) <span class="bold">Rp {{ number_format($order->total, 0) }}</span></p>
        <p>Tax 5% <span class="bold">Rp {{ number_format($tax, 1) }}</span></p>
        <p class="bold">Grand Total <span class="bold">Rp {{ number_format($grandTotal, 2) }}</span></p>
        <p>Payment <span class="bold">{{ $order->payment_method ?? 'Cash' }}</span></p>
    </div>

    <div class="thankyou">
        THANK YOU FOR ORDERED<br>
        <strong>Please use {{ $order->order_number }} as a further reference number</strong><br>
        Website: <a href="https://www.lapilaboratories.com">www.lapilaboratories.com</a>
    </div>

</body>

</html>
