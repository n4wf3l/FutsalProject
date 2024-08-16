<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Tribune;
use Stripe\Checkout\Session as StripeSession; // Alias pour Stripe Session
use Stripe\Stripe; // Pour configurer la clé API de Stripe
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        $session = StripeSession::create([
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
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'tribune_id' => $request->get('tribune_id'),
                'quantity' => $request->get('quantity') ?? 1,
            ],
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
    return view('payment.cancel')->with('error', 'The payment was cancelled or failed.');
}

    // Méthode pour générer le PDF
    protected function generatePDF($reservationDetails)
    {
        // Chemin du PDF dans le stockage public
        $pdfPath = 'reservations/' . $reservationDetails['reservation_id'] . '.pdf';

        // Générer le PDF avec la vue 'reservation.pdf' et les données de réservation
        $pdf = Pdf::loadView('reservation.pdf', compact('reservationDetails'));
        
        // Sauvegarder le PDF dans le stockage public
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Retourner le chemin du fichier PDF
        return $pdfPath;
    }

    public function downloadPDF($id)
    {
        // Générer le chemin du PDF
        $pdfPath = 'reservations/' . $id . '.pdf';

        // Vérifier si le fichier existe et le retourner
        if (Storage::disk('public')->exists($pdfPath)) {
            return response()->file(Storage::disk('public')->path($pdfPath));
        }

        // Si le fichier n'existe pas, retourner une erreur
        return abort(404, 'PDF not found');
    }
}
