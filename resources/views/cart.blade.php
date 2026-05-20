<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventTicket - Carrello</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
    <style>
        .cart-container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .cart-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .cart-header h1 {
            font-size: 36px;
            color: var(--secondary-bg-color);
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .cart-table th, .cart-table td {
            padding: 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .cart-table th {
            background-color: var(--secondary-bg-color);
            color: white;
            font-weight: 500;
        }
        .cart-table td {
            color: #555;
            vertical-align: middle;
        }
        .cart-table tr:last-child td {
            border-bottom: none;
        }
        .item-name {
            font-weight: bold;
            color: var(--text-dark);
            font-size: 18px;
        }
        .btn-remove {
            background: none;
            border: none;
            color: #ff4d4d;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }
        .btn-remove:hover {
            color: #cc0000;
        }
        .cart-summary {
            background: white;
            margin-top: 30px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: right;
        }
        .cart-summary h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--secondary-bg-color);
        }
        .total-price {
            font-size: 32px;
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-checkout {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-checkout:hover {
            background-color: #e65c00;
        }
        .empty-cart {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .empty-cart i {
            font-size: 60px;
            color: #ccc;
            margin-bottom: 20px;
        }
        .empty-cart p {
            font-size: 18px;
            color: #777;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    @include('partials.header')

    <div class="cart-container">
        <div class="cart-header">
            <h1><i class="fa-solid fa-cart-shopping"></i> Il Tuo Carrello</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Prezzo Unitario</th>
                        <th>Quantità</th>
                        <th>Totale</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['price'] * $details['quantity']; @endphp
                        <tr>
                            <td>
                                <div class="item-name">{{ $details['name'] }}</div>
                            </td>
                            <td>€ {{ number_format($details['price'], 2, ',', '.') }}</td>
                            <td>{{ $details['quantity'] }}</td>
                            <td><strong>€ {{ number_format($details['price'] * $details['quantity'], 2, ',', '.') }}</strong></td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-remove" title="Rimuovi dal carrello">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Totale Ordine</h3>
                <div class="total-price">€ {{ number_format($total, 2, ',', '.') }}</div>
                <button class="btn-checkout">Procedi all'Acquisto <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i></button>
            </div>
        @else
            <div class="empty-cart">
                <i class="fa-solid fa-cart-arrow-down"></i>
                <p>Il tuo carrello è attualmente vuoto.</p>
                <a href="{{ url('/') }}" class="btn loginBtn" style="padding: 10px 25px;">Scopri i nostri eventi</a>
            </div>
        @endif
    </div>

    @include('partials.footer')
    @include('partials.login')
    @include('partials.register')

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
