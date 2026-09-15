<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Support\SeoMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Coach;
use App\Models\Championship;

class PlayerController extends Controller
{
    public function publicRoster()
    {
        SeoMeta::share(
            'L\'effectif — Dina Kenitra FC',
            'L\'effectif senior de Dina Kenitra Futsal Club. Joueurs, coach et staff qui portent le maillot cette saison.'
        );

        return Inertia::render('Teams', [
            'players' => Player::orderBy('number', 'asc')->get(),
            'staff' => Staff::all(),
            'coach' => Coach::first(),
            'championship' => Championship::first(),
        ]);
    }

    public function index()
    {
        return Inertia::render('Admin/Players/Index', [
            'players' => Player::orderBy('number', 'asc')->get(),
            'upcomingBirthdays' => $this->upcomingBirthdays(),
        ]);
    }

    /**
     * The next 3 players whose birthday is coming up (today included).
     * We compute the next-birthday date server-side so the year of birth
     * never leaves the model boundary; the frontend only sees name, photo,
     * a formatted day/month label and the number of days remaining.
     */
    private function upcomingBirthdays(int $take = 3): array
    {
        $today = Carbon::now('Africa/Casablanca')->startOfDay();
        $year = $today->year;

        return Player::whereNotNull('birthdate')
            ->get(['id', 'first_name', 'last_name', 'photo', 'birthdate'])
            ->map(function ($p) use ($today, $year) {
                $bd = Carbon::parse($p->birthdate);
                // Carbon overflows Feb 29 to Mar 1 on non-leap years, which
                // is the accepted convention for civil birthdays.
                $next = Carbon::create($year, $bd->month, $bd->day, 0, 0, 0, 'Africa/Casablanca');
                if ($next->lt($today)) {
                    $next = $next->addYear();
                }
                return [
                    'id' => $p->id,
                    'first_name' => $p->first_name,
                    'last_name' => $p->last_name,
                    'photo' => $p->photo,
                    'birthday_label' => $next->locale('fr')->isoFormat('D MMMM'),
                    'days_until' => (int) $today->diffInDays($next, false),
                ];
            })
            ->sortBy('days_until')
            ->values()
            ->take($take)
            ->all();
    }

    public function create()
    {
        return Inertia::render('Admin/Players/Form', [
            'player' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthdate' => 'required|date',
            'position' => 'required|string|max:255',
            'number' => 'required|integer',
            'nationality' => 'required|string|max:255',
            'height' => 'required|integer',
            'contract_until' => 'required|date',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Player::create($data);

        return redirect()->route('players.index')->with('success', 'Joueur ajouté.');
    }

    public function edit(Player $player)
    {
        return Inertia::render('Admin/Players/Form', [
            'player' => $player->makeVisible(['birthdate', 'height']),
        ]);
    }

    public function update(Request $request, Player $player)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthdate' => 'required|date',
            'position' => 'required|string|max:255',
            'number' => 'required|integer',
            'nationality' => 'required|string|max:255',
            'height' => 'required|integer',
            'contract_until' => 'required|date',
        ]);

        if ($request->hasFile('photo')) {
            if ($player->photo) {
                Storage::disk('public')->delete($player->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $player->update($data);

        return redirect()->route('players.index')->with('success', 'Joueur mis à jour.');
    }

    public function destroy(Player $player)
    {
        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }

        $player->delete();

        return redirect()->route('players.index')->with('success', 'Joueur supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:players,id',
        ]);

        $players = Player::whereIn('id', $data['ids'])->get();
        foreach ($players as $player) {
            if ($player->photo) {
                Storage::disk('public')->delete($player->photo);
            }
            $player->delete();
        }

        return redirect()->route('players.index')->with('success', count($players) . ' joueur(s) supprimé(s).');
    }

    public function dashboard()
    {
        return redirect()->route('dashboard');
    }
}
