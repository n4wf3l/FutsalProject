<?php

namespace App\Support;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class SeoMeta
{
    public static function share(
        string $title,
        string $description,
        ?string $image = null,
        string $type = 'website'
    ): void {
        View::share('seoMeta', [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'type' => $type,
            'url' => url()->current(),
        ]);
    }

    public static function fromHtml(string $html, int $limit = 200): string
    {
        return Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags($html ?? ''))),
            $limit
        );
    }
}
