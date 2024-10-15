<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Tribune;
use App\Models\ClubInfo;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation; 

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        // Récupération de la clé API Stripe via config()
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    
        $tribuneId = $request->get('tribune_id');
        $totalAmount = $request->get('total_amount') * 100;
    
        // Récupérer la tribune pour obtenir la devise
        $tribune = Tribune::find($tribuneId);
    
        // Remplacer "dh" par "mad" si c'est du Dirham marocain
$currency = strtolower($tribune->currency);

// Vérifie et ajuste la devise pour qu'elle soit compatible avec Stripe
if ($currency == '€' || $currency == 'eur') {
    $currency = 'eur';
} elseif ($currency == 'dh' || $currency == 'mad') {
    $currency = 'mad';
} elseif ($currency == '$' || $currency == 'usd') {
    $currency = 'usd';
}
    
        // Log the ID, amount, and currency to verify
        Log::info("Creating Stripe session for Tribune ID: {$tribuneId}, Amount: {$totalAmount}, Currency: {$currency}");
    
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
    $clubInfo = ClubInfo::first();
    $clubName = $clubInfo->club_name;
    $clubPrefix = substr($clubName, 0, 4);

    // Déboguer pour vérifier le clubName et le clubPrefix
    Log::info("Club Prefix: $clubPrefix");

    // Récupérer le prochain match
    $nextGame = Game::where('match_date', '>=', now()->startOfDay())
        ->whereHas('homeTeam', function ($query) use ($clubPrefix) {
            $query->where('name', 'LIKE', "$clubPrefix%");
        })
        ->orderBy('match_date', 'asc')
        ->first();

    if (!$nextGame) {
        Log::info("No game found for the given prefix and date.");
    } else {
        Log::info("Next game found: " . $nextGame->homeTeam->name . " vs " . $nextGame->awayTeam->name . " on " . $nextGame->match_date);
    }

    $session_id = $request->get('session_id');
    if ($session_id) {
        // Utilisation de la clé API via config()
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::retrieve($session_id);

        $tribuneId = $session->metadata->tribune_id;
        $quantityReserved = $session->metadata->quantity;

        if ($tribuneId) {
            $tribune = Tribune::find($tribuneId);

            if ($tribune && $tribune->available_seats >= $quantityReserved) {
                $tribune->available_seats -= $quantityReserved;
                $tribune->save();

                // Détails de la réservation
                $reservationDetails = [
                    'name' => $session->customer_details->name ?? 'Unknown',
                    'email' => $session->customer_details->email,
                    'amount' => $session->amount_total / 100,
                    'currency' => strtoupper($session->currency),
                    'date' => now()->format('d-m-Y'),
                    'reservation_id' => uniqid('res_', true),
                    'game' => $nextGame,
                    'seats_reserved' => $quantityReserved,
                    'clubName' => $clubName,
                    'tribune_name' => $tribune->name,  // Ajout du type de ticket
                ];

                // Enregistrer les détails dans un fichier
                $this->saveReservationToFile($reservationDetails);

                // Générer le QR code en SVG et l'encoder en base64
                $qrCode = base64_encode(QrCode::format('svg')->size(300)->generate(json_encode($reservationDetails)));

                // Générer le PDF avec le QR code et les détails de la réservation
                $pdfPath = $this->generatePDF($reservationDetails, $qrCode);

                Mail::to($reservationDetails['email'])->send(new ReservationConfirmation($reservationDetails, $pdfPath, $qrCode));

                // Rediriger vers la page de succès avec les détails et le lien vers le PDF
                return view('payment.success', compact('qrCode', 'reservationDetails', 'pdfPath', 'clubName'));
            } else {
                return redirect()->route('fanshop.index')->with('error', 'Not enough seats available.');
            }
        }
    }

    return redirect()->route('fanshop.index')->with('error', 'Session ID not found.');
}

protected function saveReservationToFile($details)
{
    $filePath = storage_path('app/reservations.json');
    $reservations = [];

    // Vérifier si le fichier existe et lire son contenu
    if (file_exists($filePath)) {
        $json = file_get_contents($filePath);
        $reservations = json_decode($json, true) ?? [];
    }

    // Ajouter la nouvelle réservation
    $reservations[] = $details;

    // Écrire les données mises à jour dans le fichier
    file_put_contents($filePath, json_encode($reservations));
}
    



    public function cancel()
    {
        return view('payment.cancel')->with('error', 'The payment was cancelled or failed.');
    }

    // Méthode pour générer le PDF
    protected function generatePDF($reservationDetails, $qrCode)
    {
        // Pass the QR code as SVG to the view
        $pdf = Pdf::loadView('reservation.pdf', compact('reservationDetails', 'qrCode'));
    
        // Save the PDF
        $pdfPath = 'reservations/' . $reservationDetails['reservation_id'] . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
    
        return $pdfPath;
    }

    public function downloadPDF($id)
    {
        $reservationDetails = $this->getReservationDetailsById($id);
    
        if ($reservationDetails) {
            // Générer le PDF
            $pdf = Pdf::loadView('reservation.pdf', compact('reservationDetails'));
            
            // Télécharger le PDF
            return $pdf->download('reservation_' . $id . '.pdf');
        }
    
        return abort(404, 'Reservation not found');
    }
    
    private function getReservationDetailsById($id)
    {
        // Logique pour récupérer les détails de la réservation par ID
        // Ici, vous devrez implémenter la logique pour récupérer la réservation correspondante
        return [
            // Détails fictifs pour l'exemple
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'amount' => 50.00,
            'currency' => 'USD',
            'date' => now()->format('d-m-Y'),
            'reservation_id' => $id,
            'game' => $nextGame, // Si applicable
            'seats_reserved' => 1,
            'clubName' => 'Your Club Name',
        ];
    }
}
