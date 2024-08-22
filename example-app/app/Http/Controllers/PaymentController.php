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
        \Stripe\Stripe::setApiKey('sk_test_51PoCDwHaSFkMdGfiwpyIeLHHVcxuhO2vCyLCLZafHac5qs4UvVyWYjUJMkLnoVDJV6smFGYVc1zefK6QJy8FVD1400o504aQsu');
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
    // Récupérer les informations du club pour déterminer le prochain match pertinent
    $clubInfo = ClubInfo::first();
    $clubName = $clubInfo->club_name ?? 'Dina Kénitra FC';
    $clubPrefix = substr($clubName, 0, 4);
    
    // Récupérer le prochain match, soit à domicile soit à l'extérieur
    $nextGame = Game::where('match_date', '>=', now()->startOfDay())
        ->where(function ($query) use ($clubPrefix) {
            $query->whereHas('homeTeam', function ($subQuery) use ($clubPrefix) {
                $subQuery->where('name', 'LIKE', "$clubPrefix%");
            })->orWhereHas('awayTeam', function ($subQuery) use ($clubPrefix) {
                $subQuery->where('name', 'LIKE', "$clubPrefix%");
            });
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
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = StripeSession::retrieve($session_id);

        $tribuneId = $session->metadata->tribune_id;
        $quantityReserved = $session->metadata->quantity;

        if ($tribuneId) {
            $tribune = Tribune::find($tribuneId);

            if ($tribune && $tribune->available_seats >= $quantityReserved) {
                $tribune->available_seats -= $quantityReserved;
                $tribune->save();
            } else {
                return redirect()->route('fanshop.index')->with('error', 'Not enough seats available.');
            }

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
            ];

            $downloadUrl = route('download-pdf', ['id' => $reservationDetails['reservation_id']]);

            // Générer le QR code en SVG et l'encoder en base64
            $qrCode = base64_encode(QrCode::format('svg')->size(300)->generate(json_encode($reservationDetails)));

            // Générer le PDF avec le QR code et les détails de la réservation
            $pdfPath = $this->generatePDF($reservationDetails, $qrCode);

            Mail::to($reservationDetails['email'])->send(new ReservationConfirmation($reservationDetails, $pdfPath, $qrCode));

            // Rediriger vers la page de succès avec les détails et le lien vers le PDF
            return view('payment.success', compact('qrCode', 'reservationDetails', 'pdfPath', 'clubName'));
        }
    }

    return redirect()->route('fanshop.index')->with('error', 'Session ID not found.');
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
