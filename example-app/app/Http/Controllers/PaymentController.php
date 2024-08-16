<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Ticket for ' . $request->get('tribune_name'),
                    ],
                    'unit_amount' => $request->get('total_amount') * 100, // Stripe amount is in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $reservationDetails = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'amount' => 50.00,
            'currency' => '€',
            'date' => now()->toDateTimeString(),
            'reservation_id' => uniqid('res_', true),
        ];

        $qrCode = QrCode::size(300)->generate(json_encode($reservationDetails));

        return view('payment.success', compact('qrCode', 'reservationDetails'));
    }

    public function cancel()
    {
        return view('payment.cancel');
    }
}