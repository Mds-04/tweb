<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $evento = Event::findOrFail($id);
        $quantitaRichiesta = $request->quantity;

        if ($evento->biglietti_disponibili < $quantitaRichiesta) {
            return redirect()->back()->withErrors(['cart' => 'La quantità richiesta supera la disponibilità attuale.']);
        }

        $cart = session()->get('cart', []);

        // Calcolo eventuale prezzo scontato
        $prezzo_finale = $evento->prezzo;
        $sconto_applicato = false;
        
        if ($evento->sconto_giorni > 0 && $evento->sconto_percentuale > 0) {
            $giorni_mancanti = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($evento->data), false);
            if ($giorni_mancanti >= 0 && $giorni_mancanti <= $evento->sconto_giorni) {
                $prezzo_finale = $evento->prezzo * (1 - ($evento->sconto_percentuale / 100));
                $sconto_applicato = true;
            }
        }

        if(isset($cart[$id])) {
            $nuovaQuantita = $cart[$id]['quantity'] + $quantitaRichiesta;
            if ($evento->biglietti_disponibili < $nuovaQuantita) {
                return redirect()->back()->withErrors(['cart' => 'Non puoi aggiungere altri biglietti per questo evento. Disponibilità superata.']);
            }
            $cart[$id]['quantity'] = $nuovaQuantita;
        } else {
            $cart[$id] = [
                "name" => $evento->titolo,
                "quantity" => $quantitaRichiesta,
                "price" => $prezzo_finale,
                "original_price" => $evento->prezzo,
                "sconto_applicato" => $sconto_applicato
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Aggiunto al carrello con successo!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            $evento = Event::findOrFail($id);
            if ($evento->biglietti_disponibili < $request->quantity) {
                return redirect()->back()->withErrors(['cart' => 'Disponibilità non sufficiente per l\'evento: ' . $evento->titolo]);
            }
            
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Quantità aggiornata.');
        }

        return redirect()->back()->withErrors(['cart' => 'Elemento non trovato nel carrello.']);
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

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Il carrello è vuoto.']);
        }

        $request->validate([
            'metodo_pagamento' => 'required|string|in:PayPal,Carta di Credito,Klarna,Bonifico'
        ]);

        $codice_ordine = 'ORD-' . strtoupper(Str::random(10));
        $purchases = [];

        try {
            DB::transaction(function () use ($cart, $request, $codice_ordine, &$purchases) {
                foreach ($cart as $id => $details) {
                    // Lock for update per prevenire race conditions
                    $evento = Event::lockForUpdate()->findOrFail($id);

                    if ($evento->biglietti_disponibili < $details['quantity']) {
                        throw new \Exception('Spiacenti, i biglietti per "' . $evento->titolo . '" non sono più disponibili nelle quantità richieste.');
                    }

                    // Decremento disponibilità
                    $evento->biglietti_disponibili -= $details['quantity'];
                    $evento->save();

                    // Creazione acquisto
                    $acquisto = Purchase::create([
                        'cliente_id' => Auth::id(),
                        'evento_id' => $evento->id,
                        'codice_ordine' => $codice_ordine,
                        'num_biglietti' => $details['quantity'],
                        'metodo_pagamento' => $request->metodo_pagamento,
                        'prezzo_unitario' => $details['price'],
                        'totale' => $details['price'] * $details['quantity'],
                        'sconto_applicato' => $details['sconto_applicato'] ?? false,
                    ]);

                    $purchases[] = [
                        'titolo' => $evento->titolo,
                        'quantita' => $details['quantity'],
                        'totale' => $acquisto->totale
                    ];
                }
            });

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->withErrors(['cart' => $e->getMessage()]);
        }

        // Svuota carrello
        session()->forget('cart');

        // Salva dettagli ordine per la pagina di successo
        session()->flash('checkout_success', true);
        session()->flash('codice_ordine', $codice_ordine);
        session()->flash('metodo_pagamento', $request->metodo_pagamento);
        session()->flash('purchases', $purchases);

        return redirect()->route('cart.success');
    }

    public function success()
    {
        if (!session('checkout_success')) {
            return redirect()->route('home');
        }

        return view('checkout_success');
    }
}
