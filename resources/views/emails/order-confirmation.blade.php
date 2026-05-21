<x-mail::message>
# Szanowny Panie/Pani {{ $order->name }}

Dziękujemy za dokonanie zakupów w naszym sklepie internetowym **Kericho Gold**. Twoje zamówienie zostało złożone.

**Numer zamówienia:** {{ $order->order_number }}  
**Data zamówienia:** {{ $order->created_at->format('d.m.Y H:i') }}  
**Forma płatności:** {{ $order->payment_method_label }} 
@if(trim(strtoupper($order->payment_method)) === 'COD')
Zamówienie zostanie zrealizowane niezwłocznie. Zapłacisz kurierowi przy odbiorze przesyłki.
@elseif($order->status === 'pending')
Po zaksięgowaniu wpłaty zamówienie zostanie zrealizowane.
@endif

**Sposób wysyłki:** {{ $order->shipping_data['name'] ?? $order->shipping_method }}  
**Dokument sprzedaży:** {{ $order->wants_invoice ? 'Faktura (NIP: '.$order->nip.')' : 'Faktura' }}

## Szczegóły zamówienia:

<x-mail::table>
| Produkt | Ilość | Cena | Wartość |
| :--- | :---: | :---: | :--- |
@foreach($order->items as $item)
| [{{ $item->product_name }}]({{ config('app.url') }}/product/{{ $item->product?->slug ?? '#' }}) | {{ $item->quantity }} | {{ number_format($item->price, 2, ',', ' ') }} zł | {{ number_format($item->total, 2, ',', ' ') }} zł |
@endforeach
| **Koszt wysyłki** | | | **{{ number_format($order->shipping_cost, 2, ',', ' ') }} zł** |
| **Razem do zapłaty** | | | **{{ number_format($order->total, 2, ',', ' ') }} zł** |
</x-mail::table>

**Adres dostawy:**  
{{ $order->name }}  
{{ $order->address }}  
{{ $order->zip }} {{ $order->city }}  
@if(isset($order->shipping_data['parcel_locker']))
**Paczkomat:** {{ $order->shipping_data['parcel_locker'] }}
@endif

**Dodatkowe informacje:**  
Treść maila stanowi potwierdzenie, że sklep otrzymał złożone przez Panią/Pana zamówienie i jest tożsama z informacją wyświetlającą się na stronie internetowej naszego sklepu. Wszystkie informacje dotyczące statusu zamówienia otrzymają Państwo e-mailem.

**UWAGA:** Ten adres został podany przez osobę dokonującą zakupów w naszym sklepie. Jeżeli to nie Ty podałeś ten adres prosimy o pilny kontakt z nami w celu anulowania transakcji. Jeśli zaś wszystkie dane transakcji się zgadzają, serdecznie dziękujemy Ci za dokonanie zakupów w naszym sklepie internetowym.

Z poważaniem,  
**Kericho Gold Polska**  
Trans-Tok Logistic Group Sp. z o.o.  
ul. Sławęcińska 14, Macierzysz, 05-850 Ożarów Mazowiecki  
Adres email: kontakt@kerichogold.pl  
NIP: 1182104012  
Konto bankowe: [PROSZĘ UZUPEŁNIĆ NUMER KONTA BANKOWEGO]

</x-mail::message>
