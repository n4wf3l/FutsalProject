<?php

namespace App\Http\Middleware;

use App\Models\ClubInfo;
use App\Models\FlashMessage;
use App\Models\Interview;
use App\Models\PlayerApplication;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'club' => fn () => $this->clubData(),
            'sponsors' => fn () => $this->sponsorList(),
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'flashMessage' => fn () => FlashMessage::latest()->first(),
            'app' => [
                'env' => app()->environment(),
                'locale' => $locale,
                'direction' => in_array($locale, ['ar'], true) ? 'rtl' : 'ltr',
            ],
            // Counters displayed as badges in the admin sidebar.
            // Only computed for authenticated users, defensive against
            // missing tables (fresh clone / migration pending).
            'adminInbox' => fn () => $request->user() ? $this->adminInboxCounts() : null,
        ];
    }

    private function clubData(): array
    {
        $info = ClubInfo::first();
        $name = $info->club_name ?? 'Dina Kénitra FC';

        return [
            'name' => $name,
            'prefix' => substr($name, 0, 4),
            'city' => $info->city ?? 'Kénitra',
            'location' => $info->sportcomplex_location ?? 'Complexe Sportif Municipal',
            'phone' => $info->phone ?? null,
            'email' => $info->email ?? 'contact@dinakenitrafc.ma',
            'president' => $info->president ?? null,
            'facebook' => $info->facebook ?? null,
            'instagram' => $info->instagram ?? null,
            'latitude' => $info->latitude ?? null,
            'longitude' => $info->longitude ?? null,
            'federation_logo' => $info && $info->federation_logo ? asset('storage/' . $info->federation_logo) : null,
            'organization_logo' => $info && $info->organization_logo ? asset('storage/' . $info->organization_logo) : null,
        ];
    }

    private function sponsorList(): array
    {
        if (! Schema::hasTable('sponsors')) {
            return [];
        }

        return Sponsor::orderBy('name')
            ->get(['id', 'name', 'logo', 'website'])
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'logo' => $s->logo ? asset('storage/' . $s->logo) : null,
                'website' => $s->website,
            ])
            ->all();
    }

    private function adminInboxCounts(): array
    {
        $applicationsPending = Schema::hasTable('player_applications')
            ? PlayerApplication::where('status', PlayerApplication::STATUS_PENDING)->count()
            : 0;

        $interviewsDraft = Schema::hasTable('interviews')
            ? Interview::whereNull('published_at')->count()
            : 0;

        return [
            'applicationsPending' => $applicationsPending,
            'interviewsDraft' => $interviewsDraft,
            'total' => $applicationsPending + $interviewsDraft,
        ];
    }
}
