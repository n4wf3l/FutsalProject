<?php

namespace App\Http\Controllers;

use App\Models\PlayerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PlayerApplicationController extends Controller
{
    public const POSITIONS = [
        'Gardien',
        'Défenseur',
        'Milieu',
        'Ailier',
        'Pivot',
        'Attaquant',
        'Polyvalent',
    ];

    // ————————————————— Public —————————————————
    public function create()
    {
        return Inertia::render('Rejoindre', [
            'categories' => PlayerApplication::CATEGORIES,
            'positions' => self::POSITIONS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:120',
            'last_name' => 'required|string|max:120',
            'email' => 'required|email|max:180',
            'phone' => 'required|string|max:40',
            'birthdate' => 'required|date|before:today',
            'nationality' => 'nullable|string|max:80',
            'city' => 'nullable|string|max:120',
            'category' => 'required|in:'.implode(',', array_keys(PlayerApplication::CATEGORIES)),
            'position_preference' => 'nullable|string|max:60',
            'current_club' => 'nullable|string|max:180',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'message' => 'nullable|string|max:2000',
            'cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'consent' => 'accepted',
            'parental_consent' => 'nullable|boolean',
        ], [
            'consent.accepted' => 'Ton consentement est requis pour envoyer la candidature.',
        ]);

        // Minor detection: parental consent mandatory
        $birth = new \DateTime($data['birthdate']);
        $age = $birth->diff(new \DateTime())->y;
        if ($age < 18 && empty($data['parental_consent'])) {
            throw ValidationException::withMessages([
                'parental_consent' => 'Le consentement parental est obligatoire pour les mineurs.',
            ]);
        }

        if ($request->hasFile('cv')) {
            // Stored on the private "local" disk (not exposed via public/storage).
            // Access is granted only via the authenticated streamCv() route below.
            $data['cv_path'] = $request->file('cv')->store('applications/cv', 'local');
        }
        unset($data['cv'], $data['consent'], $data['parental_consent']);

        $data['status'] = PlayerApplication::STATUS_PENDING;
        $data['deletion_token'] = Str::random(48);

        $application = PlayerApplication::create($data);

        // Confirmation email with the RGPD-style self-service link.
        try {
            $manageUrl = url('/candidature/'.$application->deletion_token);
            Mail::raw(
                "Bonjour {$application->first_name},\n\n".
                "Ta candidature à Dina Kenitra FC a bien été enregistrée. Notre staff sportif va l'étudier et te recontactera par email.\n\n".
                "Conformément à la Loi 09-08 sur la protection des données au Maroc, tu peux à tout moment consulter les données que nous avons enregistrées ou en demander la suppression via ce lien personnel :\n\n".
                "{$manageUrl}\n\n".
                "Garde ce lien en lieu sûr — il donne accès à ton dossier de candidature.\n\n".
                "Sportivement,\nDina Kenitra FC",
                function ($m) use ($application) {
                    $m->to($application->email, "{$application->first_name} {$application->last_name}")
                        ->subject('Ta candidature à Dina Kenitra FC — accès à tes données');
                }
            );
        } catch (\Throwable $e) {
            // Do not block the submission if the mail transport fails.
            // Log without leaking exception message that may contain email/SMTP context.
            \Log::warning('Application confirmation email failed', [
                'application_id' => $application->id,
                'exception_class' => get_class($e),
            ]);
        }

        return redirect()->route('rejoindre.create')
            ->with('success', 'Ta candidature a été envoyée. Un email de confirmation avec le lien de gestion de tes données vient de partir.');
    }

    // ————————————————— Self-service (RGPD-like / Loi 09-08) —————————————————
    public function showByToken(string $token)
    {
        $application = PlayerApplication::where('deletion_token', $token)->firstOrFail();

        return Inertia::render('CandidatureManage', [
            'application' => [
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'category' => $application->categoryLabel(),
                'status' => $application->status,
                'created_at' => $application->created_at,
                'has_cv' => (bool) $application->cv_path,
                'deletion_token' => $token,
            ],
        ]);
    }

    public function destroyByToken(string $token)
    {
        $application = PlayerApplication::where('deletion_token', $token)->firstOrFail();

        if ($application->cv_path) {
            Storage::disk('local')->delete($application->cv_path);
        }
        $application->delete();

        return redirect()->route('candidature.deletion.request')
            ->with('success', 'Tes données ont été supprimées définitivement. Merci.');
    }

    public function requestDeletion()
    {
        return Inertia::render('CandidatureDeletionRequest');
    }

    public function sendDeletionLink(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:180',
        ]);

        $applications = PlayerApplication::where('email', $data['email'])
            ->whereNotNull('deletion_token')
            ->get();

        // Always show the same success message to avoid email enumeration.
        foreach ($applications as $application) {
            try {
                $manageUrl = url('/candidature/'.$application->deletion_token);
                Mail::raw(
                    "Bonjour,\n\n".
                    "Voici ton lien personnel pour consulter ou supprimer ta candidature à Dina Kenitra FC :\n\n".
                    "{$manageUrl}\n\n".
                    "Ce lien reste valable tant que ta candidature est active. Si tu n'es pas à l'origine de cette demande, ignore ce message.\n\n".
                    "Dina Kenitra FC",
                    function ($m) use ($application) {
                        $m->to($application->email)
                            ->subject('Ton lien de gestion — Candidature Dina Kenitra FC');
                    }
                );
            } catch (\Throwable $e) {
                \Log::warning('Deletion link email failed', [
                    'application_id' => $application->id,
                    'exception_class' => get_class($e),
                ]);
            }
        }

        return back()->with('success', 'Si une candidature existe pour cette adresse, un email vient de partir avec le lien de gestion.');
    }

    // ————————————————— Admin —————————————————
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $category = $request->string('category')->toString();

        $query = PlayerApplication::latest();
        if ($status !== '') $query->where('status', $status);
        if ($category !== '') $query->where('category', $category);

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $query->get(),
            'statuses' => PlayerApplication::STATUSES,
            'categories' => PlayerApplication::CATEGORIES,
            'filters' => ['status' => $status, 'category' => $category],
            'counts' => [
                'total' => PlayerApplication::count(),
                'pending' => PlayerApplication::where('status', PlayerApplication::STATUS_PENDING)->count(),
                'contacted' => PlayerApplication::where('status', PlayerApplication::STATUS_CONTACTED)->count(),
                'accepted' => PlayerApplication::where('status', PlayerApplication::STATUS_ACCEPTED)->count(),
                'rejected' => PlayerApplication::where('status', PlayerApplication::STATUS_REJECTED)->count(),
            ],
        ]);
    }

    public function show(PlayerApplication $application)
    {
        $application->load('reviewer:id,name');

        return Inertia::render('Admin/Applications/Show', [
            'application' => $application,
            'statuses' => PlayerApplication::STATUSES,
            'categories' => PlayerApplication::CATEGORIES,
        ]);
    }

    public function updateStatus(Request $request, PlayerApplication $application)
    {
        $data = $request->validate([
            'status' => 'required|in:'.implode(',', PlayerApplication::STATUSES),
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? $application->admin_notes,
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(PlayerApplication $application)
    {
        if ($application->cv_path) {
            Storage::disk('local')->delete($application->cv_path);
        }
        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Candidature supprimée.');
    }

    /**
     * Stream a candidate CV from the private disk. Auth is enforced at the route level.
     */
    public function streamCv(PlayerApplication $application)
    {
        abort_unless($application->cv_path, 404);
        abort_unless(Storage::disk('local')->exists($application->cv_path), 404);

        return Storage::disk('local')->download(
            $application->cv_path,
            'cv-'.$application->first_name.'-'.$application->last_name.'.'.pathinfo($application->cv_path, PATHINFO_EXTENSION),
        );
    }
}
