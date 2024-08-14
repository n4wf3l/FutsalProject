<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\UserSetting;
use App\Models\ClubInfo;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer('*', function ($view) {
            $siteSettings = UserSetting::where('user_id', 1)->first();
            
            // Récupérer les informations du club
            $clubInfo = ClubInfo::first();

            $primaryColor = $siteSettings->theme_color_primary ?? '#1F2937';
            $secondaryColor = $siteSettings->theme_color_secondary ?? '#FF0000';
            $logoPath = $siteSettings && $siteSettings->logo ? asset('storage/' . $siteSettings->logo) : null;
            $clubName = $siteSettings->club_name ?? 'Default Club Name';

            // Assurez-vous que les informations du club sont bien récupérées
            $clubLocation = $clubInfo->sportcomplex_location ?? 'Default Location';
            $phone = $clubInfo->phone ?? 'No phone available';
            $email = $clubInfo->email ?? 'No email available';
            $federationLogo = $clubInfo->federation_logo ? asset('storage/' . $clubInfo->federation_logo) : null;
            $facebook = $clubInfo->facebook ?? '#';
            $instagram = $clubInfo->instagram ?? '#';

            $view->with(compact('primaryColor', 'secondaryColor', 'logoPath', 'clubName', 'clubLocation', 'phone', 'email', 'federationLogo', 'facebook', 'instagram'));
        });
    }

    public function register()
    {
        //
    }
}

