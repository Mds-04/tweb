<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acquisto Completato - EventTicket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
    <style>
        .success-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 80px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .success-title {
            font-size: 32px;
            color: var(--secondary-bg-color);
            margin-bottom: 10px;
        }

        .success-subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }

        .order-details {
            background: #f9f9f9;
            border-radius: 15px;
            padding: 30px;
            text-align: left;
            margin-bottom: 40px;
        }

        .order-meta {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .meta-item span {
            display: block;
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .meta-item strong {
            font-size: 18px;
            color: var(--text-dark);
        }

        .items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-title {
            font-size: 16px;
            font-weight: bold;
            color: var(--secondary-bg-color);
            margin-bottom: 5px;
        }

        .item-qty {
            font-size: 14px;
            color: #666;
        }

        .item-total {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .grand-total {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: var(--text-dark);
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #ccc;
        }
    </style>
</head>

<body style="background-color: #f4f7f6;">

    @include('partials.header')

    <div class="success-container">
        <i class="fa-solid fa-circle-check success-icon"></i>
        <h1 class="success-title">Acquisto Completato!</h1>
        <p class="success-subtitle">Grazie per aver scelto EventTicket. Il tuo ordine è confermato.</p>

        @if (session('codice_ordine') && session('purchases'))
            <div class="order-details">
                <div class="order-meta">
                    <div class="meta-item">
                        <span>Codice Ordine</span>
                        <strong>{{ session('codice_ordine') }}</strong>
                    </div>
                    <div class="meta-item" style="text-align: right;">
                        <span>Metodo di Pagamento</span>
                        <strong>{{ session('metodo_pagamento') }}</strong>
                    </div>
                </div>

                <ul class="items-list">
                    @php $grandTotal = 0; @endphp
                    @foreach (session('purchases') as $purchase)
                        @php $grandTotal += $purchase['totale']; @endphp
                        <li class="item-row">
                            <div class="item-info">
                                <div class="item-title">{{ $purchase['titolo'] }}</div>
                                <div class="item-qty">{{ $purchase['quantita'] }} biglietti</div>
                            </div>
                            <div class="item-total">
                                € {{ number_format($purchase['totale'], 2, ',', '.') }}
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="grand-total">
                    Totale Pagato: € {{ number_format($grandTotal, 2, ',', '.') }}
                </div>
            </div>
        @endif

        <a href="{{ url('/') }}" class="btn loginBtn"
            style="padding: 15px 40px; font-size: 18px; border-radius: 30px;">
            Torna alla Home
        </a>
    </div>

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
