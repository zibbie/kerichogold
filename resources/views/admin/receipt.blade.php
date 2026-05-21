<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paragon - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #eee;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .info-block h3 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #f9f9f9;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .total-row.grand-total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        @media print {
            .no-print { display: none; }
            .container { border: none; padding: 0; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #000; color: #fff; border: none; border-radius: 5px;">Drukuj Paragon</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; background: #eee; border: 1px solid #ccc; border-radius: 5px; margin-left: 10px;">Zamknij</button>
    </div>

    <div class="container">
        <div class="header">
            <h1>KERICHO GOLD</h1>
            <p>Potwierdzenie Zamówienia (Paragon)</p>
        </div>

        <div class="info-grid">
            <div class="info-block">
                <h3>Sprzedawca</h3>
                <p><strong>Trans-Tok Logistic Group Sp. z o.o.</strong><br>
                ul. Sławęcińska 14, Macierzysz<br>
                05-850 Ożarów Mazowiecki<br>
                NIP: 1182104012, KRS: 0000547164</p>
            </div>
            <div class="info-block" style="text-align: right;">
                <h3>Zamówienie</h3>
                <p>Numer: <strong>{{ $order->order_number }}</strong><br>
                Data: {{ $order->created_at->format('d.m.Y H:i') }}<br>
                Status: {{ strtoupper($order->status) }}<br>
                Płatność: {{ strtoupper($order->payment_method) }}</p>
            </div>
        </div>

        <div class="info-block">
            <h3>Nabywca</h3>
            <p><strong>{{ $order->name }}</strong><br>
            {{ $order->email }}<br>
            {{ $order->phone }}<br>
            {{ $order->zip }} {{ $order->city }}<br>
            {{ $order->shipping_address['address'] ?? '' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th style="text-align: center;">Ilość</th>
                    <th style="text-align: right;">Cena</th>
                    <th style="text-align: right;">Suma</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->price, 2) }} PLN</td>
                    <td style="text-align: right;">{{ number_format($item->total, 2) }} PLN</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span>Wartość netto:</span>
                <span>{{ number_format($order->total / 1.23, 2) }} PLN</span>
            </div>
            <div class="total-row">
                <span>VAT (23%):</span>
                <span>{{ number_format($order->total - ($order->total / 1.23), 2) }} PLN</span>
            </div>
            <div class="total-row">
                <span>Koszt dostawy:</span>
                <span>{{ number_format($order->shipping_cost, 2) }} PLN</span>
            </div>
            <div class="total-row grand-total">
                <span>DO ZAPŁATY:</span>
                <span>{{ number_format($order->total, 2) }} PLN</span>
            </div>
        </div>

        <div class="footer">
            <p>Dziękujemy za zakupy w Kericho Gold!<br>
            To jest dokument niefiskalny generowany automatycznie.</p>
        </div>
    </div>
</body>
</html>
