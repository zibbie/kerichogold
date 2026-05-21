<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jesteś Offline — Kericho Gold</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700&family=Be+Vietnam+Pro:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        h1 { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f7faf5] min-h-screen flex items-center justify-center p-6 text-center">
    <div class="max-w-md">
        <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto mb-8 border border-[#ecefea]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#4a654e]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-[#191c1a] mb-4">Ups! Jesteś offline</h1>
        <p class="text-[#191c1a]/60 mb-8">Wygląda na to, że straciłeś połączenie z internetem. Sprawdź swoje ustawienia sieciowe i spróbuj ponownie.</p>
        <button onclick="window.location.reload()" class="bg-[#4a654e] text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:shadow-xl transition-all active:scale-95">
            Spróbuj ponownie
        </button>
    </div>
</body>
</html>
