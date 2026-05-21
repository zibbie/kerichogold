<x-mail::message>
# Nowe zamówienie w sklepie

Pojawiło się nowe zamówienie o numerze: **#{{ $order->order_number }}**

### Szczegóły zamówienia:
- **Klient:** {{ $order->name }} ({{ $order->email }})
- **Kwota:** {{ number_format($order->total, 2, ',', ' ') }} zł
- **Metoda płatności:** {{ $order->payment_method_label }}
- **Status:** {{ $order->status === 'paid' ? 'Opłacone' : ($order->status === 'pending' ? 'Oczekiwanie' : $order->status) }}

<x-mail::button :url="config('app.url') . '/admin/orders/' . $order->id">
Zobacz zamówienie w panelu
</x-mail::button>

Dziękujemy,<br>
{{ config('app.name') }}
</x-mail::message>
