<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        // Simulazione del recupero dell'evento.
        // In una situazione reale lo prenderemmo dal database: $evento = Event::findOrFail($id);
        
        $eventiFittizi = [
            1 => [
                'titolo' => 'Concerto di Primavera',
                'prezzo' => 45.00,
            ],
            'default' => [
                'titolo' => 'Evento Spettacolare',
                'prezzo' => 25.50,
            ]
        ];

        // Usiamo $eventiFittizi per ora in modo che corrisponda alla route dinamica di web.php
        if (isset($eventiFittizi[$id])) {
            $evento = $eventiFittizi[$id];
        } else {
            $evento = $eventiFittizi['default'];
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $evento['titolo'],
                "quantity" => 1,
                "price" => $evento['prezzo'],
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Evento aggiunto al carrello!');
    }

    public function remove(Request $request, $id)
    {
        if($id) {
            $cart = session()->get('cart');
            if(isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Evento rimosso dal carrello');
        }
    }
}
