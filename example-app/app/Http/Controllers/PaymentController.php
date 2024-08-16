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
    $tribuneId = $request->get('tribune_id');
    $totalAmount = $request->get('total_amount') * 100;

    // Récupérer la tribune pour obtenir la devise
    $tribune = Tribune::find($tribuneId);

    // Remplacer "dh" par "mad" si c'est du Dirham marocain
    $currency = strtolower($tribune->currency) == 'dh' ? 'mad' : strtolower($tribune->currency);

    // Log the ID, amount, and currency to verify
    Log::info("Creating Stripe session for Tribune ID: {$tribuneId}, Amount: {$totalAmount}, Currency: {$currency}");

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => $currency,
                'product_data' => [
                    'name' => 'Ticket for ' . $request->get('tribune_name'),
                ],
                'unit_amount' => $totalAmount,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('payment.cancel'),
        'metadata' => [
            'tribune_id' => $tribuneId,
            'quantity' => $request->get('quantity') ?? 1,
        ],
    ]);

    Log::info("Session created with ID: {$session->id} and Tribune ID: {$tribuneId}");

    return redirect($session->url);
}

    public function success(Request $request)
{
    $session_id = $request->get('session_id');

    if ($session_id) {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = StripeSession::retrieve($session_id);

        // Récupérer l'ID de la tribune depuis les métadonnées
        $tribuneId = $session->metadata->tribune_id;
        $quantityReserved = $session->metadata->quantity;

        Log::info("Session ID: {$session_id}, Tribune ID from metadata: {$tribuneId}, Quantity Reserved: {$quantityReserved}");

        if ($tribuneId) {
            $tribune = Tribune::find($tribuneId);

            if ($tribune && $tribune->available_seats >= $quantityReserved) {
                $tribune->available_seats -= $quantityReserved;

                Log::info("Updating Tribune ID {$tribuneId} - Available Seats before save: {$tribune->available_seats}");

                $tribune->save();

                Log::info("Tribune ID {$tribuneId} - Available Seats after save: {$tribune->available_seats}");
            } else {
                Log::error("Not enough seats available for Tribune ID: {$tribuneId}");
                return redirect()->route('fanshop.index')->with('error', 'Not enough seats available.');
            }
        } else {
            Log::error("Tribune ID not found in session metadata.");
        }
    }

    // Détails de la réservation (pour affichage sur la page de succès)
    $reservationDetails = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'amount' => 50.00,
        'currency' => '€',
        'date' => now()->toDateTimeString(),
        'reservation_id' => uniqid('res_', true),
    ];

    // Générer le code QR
    $qrCode = QrCode::size(300)->generate(json_encode($reservationDetails));

    // Rediriger vers la page de succès avec les détails
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
