<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\UserSetting;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer('*', function ($view) {
            // Assurez-vous d'avoir une entrée spécifique pour les paramètres globaux (par exemple, avec user_id = 1)
            $siteSettings = UserSetting::where('user_id', 1)->first();

            // Définissez les valeurs par défaut au cas où l'entrée n'existerait pas
            $primaryColor = $siteSettings->theme_color_primary ?? '#1F2937'; // Gris par défaut
            $secondaryColor = $siteSettings->theme_color_secondary ?? '#FF0000'; // Rouge par défaut
            $logoPath = $siteSettings && $siteSettings->logo ? asset('storage/' . $siteSettings->logo) : null;
            $clubName = $siteSettings->club_name ?? 'Default Club Name';

            // Partager les variables avec toutes les vues
            $view->with(compact('primaryColor', 'secondaryColor', 'logoPath', 'clubName'));
        });
    }

    public function register()
    {
        //
    }
}
