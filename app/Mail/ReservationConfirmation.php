<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationDetails;
    public $pdfPath;
    public $qrCode;

    /**
     * Create a new message instance.
     *
     * @param array $reservationDetails
     * @param string $pdfPath
     * @param string $qrCode
     * @return void
     */
    public function __construct($reservationDetails, $pdfPath, $qrCode)
    {
        $this->reservationDetails = $reservationDetails;
        $this->pdfPath = $pdfPath;
        $this->qrCode = $qrCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reservation_confirmation')
            ->with([
                'reservationDetails' => $this->reservationDetails,
                'qrCode' => $this->qrCode,
            ])
            ->attach(storage_path('app/public/' . $this->pdfPath), [
                'as' => 'reservation_' . $this->reservationDetails['reservation_id'] . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}