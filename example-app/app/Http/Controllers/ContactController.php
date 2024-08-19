<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ClubInfo;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);
    
        // Utiliser l'email configuré dans le fichier .env ou un email statique
        $toEmail = env('MAIL_FROM_ADDRESS', 'info@nainnovations.be');
    
        // Envoyer l'email
        Mail::send([], [], function ($message) use ($request, $toEmail) {
            $message->to($toEmail)
                    ->subject('You have received a new message via the contact form.')
                    ->from($request->email, $request->firstname . ' ' . $request->lastname)
                    ->html(
                        'Name: ' . $request->firstname . ' ' . $request->lastname . '<br>' .
                        'Phone: ' . $request->phone . '<br>' .
                        'Message: ' . $request->message
                    );
        });
    
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}