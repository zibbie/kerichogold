<div class="max-w-3xl mx-auto px-4 py-16 text-center" wire:poll.3s="checkStatus">
    <div class="bg-white p-12 rounded-3xl shadow-xl border border-gray-100">
        @if(in_array($status, ['correct', 'paid', 'completed']))
            <div class="mb-8 flex justify-center">
                <div class="rounded-full bg-green-100 p-6">
                    <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Płatność udana!</h1>
            <p class="text-lg text-gray-600 mb-8">Dziękujemy za zakupy. Twoje zamówienie zostało przekazane do realizacji.</p>
            <a href="/" class="inline-block bg-black text-white px-8 py-4 rounded-xl font-bold hover:bg-gray-800 transition-colors">
                Wróć do sklepu
            </a>
        @elseif($status === 'error' || $status === 'failed')
            <div class="mb-8 flex justify-center">
                <div class="rounded-full bg-red-100 p-6">
                    <svg class="h-16 w-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Płatność nieudana</h1>
            <p class="text-lg text-gray-600 mb-8">Wystąpił problem podczas przetwarzania Twojej płatności. Prosimy spróbować ponownie lub skontaktować się z obsługą.</p>
            <a href="/checkout" class="inline-block bg-black text-white px-8 py-4 rounded-xl font-bold hover:bg-gray-800 transition-colors">
                Wróć do kasy
            </a>
        @else
            <div class="mb-8 flex justify-center">
                <div class="animate-spin rounded-full h-24 w-24 border-t-4 border-b-4 border-blue-500"></div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Oczekiwanie na potwierdzenie...</h1>
            <p class="text-lg text-gray-600">Weryfikujemy status Twojej płatności. Zazwyczaj zajmuje to kilka sekund.</p>
            <p class="text-sm text-gray-400 mt-8">ID Transakcji: {{ $transactionId }}</p>
        @endif
    </div>

    @push('scripts')
    <script>
        window.addEventListener('gtag-event', event => {
            if (event.detail?.event === 'purchase' || event.detail?.[0]?.event === 'purchase') {
                const data = event.detail?.data || event.detail?.[0]?.data;
                const tid = data?.transaction_id;
                if (tid && sessionStorage.getItem('purchase_fired_' + tid)) {
                    event.stopImmediatePropagation();
                    return;
                }
                if (tid) sessionStorage.setItem('purchase_fired_' + tid, '1');
            }
        });
    </script>
    @endpush
</div>
