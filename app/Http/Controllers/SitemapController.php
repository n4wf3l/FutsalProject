<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Interview;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $now = now()->toAtomString();

        $entries = [
            ['loc' => url('/'), 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => $now],
            ['loc' => url('/teams'), 'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => $now],
            ['loc' => url('/espoirs'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $now],
            ['loc' => url('/calendar'), 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $now],
            ['loc' => url('/news'), 'changefreq' => 'weekly', 'priority' => '0.8', 'lastmod' => $now],
            ['loc' => url('/interviews'), 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $now],
            ['loc' => url('/galleries'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $now],
            ['loc' => url('/videos'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $now],
            ['loc' => url('/fanshop'), 'changefreq' => 'weekly', 'priority' => '0.7', 'lastmod' => $now],
            ['loc' => url('/about'), 'changefreq' => 'yearly', 'priority' => '0.5', 'lastmod' => $now],
            ['loc' => url('/contact'), 'changefreq' => 'yearly', 'priority' => '0.4', 'lastmod' => $now],
            ['loc' => url('/rejoindre'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $now],
            ['loc' => url('/legal'), 'changefreq' => 'yearly', 'priority' => '0.2', 'lastmod' => $now],
            ['loc' => url('/confidentialite'), 'changefreq' => 'yearly', 'priority' => '0.2', 'lastmod' => $now],
        ];

        // Articles (with Google News extension for entries published in the last 2 days)
        $newsWindow = now()->subDays(2);
        $articleEntries = [];
        if (Schema::hasTable('articles')) {
            Article::latest()->get(['slug', 'title', 'created_at', 'updated_at'])->each(function ($article) use (&$entries, &$articleEntries, $newsWindow) {
                $entry = [
                    'loc' => url('/articles/'.$article->slug),
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                    'lastmod' => $article->updated_at?->toAtomString() ?? now()->toAtomString(),
                ];
                if ($article->created_at && $article->created_at->greaterThan($newsWindow)) {
                    $entry['news'] = [
                        'title' => $article->title,
                        'publication_date' => $article->created_at->toAtomString(),
                    ];
                }
                $entries[] = $entry;
                $articleEntries[] = $entry;
            });
        }

        if (Schema::hasTable('interviews')) {
            Interview::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->get(['slug', 'title', 'published_at', 'updated_at'])
                ->each(function ($interview) use (&$entries, $newsWindow) {
                    $entry = [
                        'loc' => url('/interviews/'.$interview->slug),
                        'changefreq' => 'monthly',
                        'priority' => '0.8',
                        'lastmod' => $interview->updated_at?->toAtomString() ?? now()->toAtomString(),
                    ];
                    if ($interview->published_at && $interview->published_at->greaterThan($newsWindow)) {
                        $entry['news'] = [
                            'title' => $interview->title,
                            'publication_date' => $interview->published_at->toAtomString(),
                        ];
                    }
                    $entries[] = $entry;
                });
        }

        if (Schema::hasTable('galleries')) {
            Gallery::latest()->get(['id', 'updated_at'])->each(function ($gallery) use (&$entries) {
                $entries[] = [
                    'loc' => url('/galleries/'.$gallery->id),
                    'changefreq' => 'monthly',
                    'priority' => '0.5',
                    'lastmod' => $gallery->updated_at?->toAtomString() ?? now()->toAtomString(),
                ];
            });
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">'."\n";
        foreach ($entries as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($entry['loc'], ENT_XML1).'</loc>'."\n";
            $xml .= '    <lastmod>'.$entry['lastmod'].'</lastmod>'."\n";
            $xml .= '    <changefreq>'.$entry['changefreq'].'</changefreq>'."\n";
            $xml .= '    <priority>'.$entry['priority'].'</priority>'."\n";
            if (isset($entry['news'])) {
                $xml .= "    <news:news>\n";
                $xml .= "      <news:publication>\n";
                $xml .= '        <news:name>Dina Kenitra FC</news:name>'."\n";
                $xml .= '        <news:language>fr</news:language>'."\n";
                $xml .= "      </news:publication>\n";
                $xml .= '      <news:publication_date>'.$entry['news']['publication_date'].'</news:publication_date>'."\n";
                $xml .= '      <news:title>'.htmlspecialchars($entry['news']['title'], ENT_XML1).'</news:title>'."\n";
                $xml .= "    </news:news>\n";
            }
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
