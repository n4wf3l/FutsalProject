<?php

namespace App\Http\Middleware;

use App\Models\ClubInfo;
use App\Models\FlashMessage;
use Illuminate\Http\Request;
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
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'club' => fn () => $this->clubData(),
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'flashMessage' => fn () => FlashMessage::latest()->first(),
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
        ];
    }
}
