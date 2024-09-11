<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\UserSetting;
use App\Models\ClubInfo;
use App\Models\Sponsor;

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

            // Vérifier si $clubInfo existe avant d'accéder à ses propriétés
            $clubLocation = $clubInfo ? $clubInfo->sportcomplex_location ?? 'Default Location' : 'Default Location';
            $phone = $clubInfo ? $clubInfo->phone ?? 'No phone available' : 'No phone available';
            $email = $clubInfo ? $clubInfo->email ?? 'No email available' : 'No email available';
            $federationLogo = $clubInfo && $clubInfo->federation_logo ? asset('storage/' . $clubInfo->federation_logo) : null;
            $facebook = $clubInfo ? $clubInfo->facebook ?? '#' : '#';
            $instagram = $clubInfo ? $clubInfo->instagram ?? '#' : '#';

            // Récupérer les sponsors
            $sponsors = Sponsor::all();

            // Partager les variables avec toutes les vues
            $view->with(compact(
                'primaryColor', 
                'secondaryColor', 
                'logoPath', 
                'clubName', 
                'clubLocation', 
                'phone', 
                'email', 
                'federationLogo', 
                'facebook', 
                'instagram',
                'sponsors' // Ajout de la variable sponsors
            ));
        });
    }

    public function register()
    {
        //
    }
}
