<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Coach;
use App\Models\Gallery;
use App\Models\Interview;
use App\Models\Player;
use App\Models\PressRelease;
use App\Models\Staff;
use App\Models\Video;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    private const PER_PAGE = 15;
    private const MIN_QUERY_LENGTH = 2;

    public function index(Request $request): Response
    {
        $rawQuery = (string) $request->get('q', '');
        $query = trim($rawQuery);
        $page = max(1, (int) $request->get('page', 1));

        SeoMeta::share(
            $query !== ''
                ? 'Recherche : '.$query.' — Dina Kenitra FC'
                : 'Recherche — Dina Kenitra FC',
            'Cherche articles, communiqués, interviews, joueurs et coachs dans tout le site de Dina Kenitra Futsal Club.'
        );

        if (mb_strlen($query) < self::MIN_QUERY_LENGTH) {
            return Inertia::render('Search', [
                'query' => $query,
                'results' => $this->emptyPaginator($request, $page),
            ]);
        }

        $like = '%'.$query.'%';
        $results = collect();

        if (Schema::hasTable('articles')) {
            Article::query()
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->limit(200)
                ->get()
                ->each(function (Article $article) use ($results) {
                    $results->push([
                        'type' => 'article',
                        'type_label' => 'Actualité',
                        'title' => $article->title,
                        'excerpt' => $this->excerpt($article->description),
                        'image' => $article->image ? '/storage/'.$article->image : null,
                        'url' => '/articles/'.$article->slug,
                        'date' => optional($article->created_at)->toIso8601String(),
                        'sort_at' => optional($article->created_at)->timestamp ?? 0,
                    ]);
                });
        }

        if (Schema::hasTable('press_releases')) {
            PressRelease::query()
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('content', 'like', $like);
                })
                ->limit(200)
                ->get()
                ->each(function (PressRelease $press) use ($results) {
                    $results->push([
                        'type' => 'press_release',
                        'type_label' => 'Communiqué',
                        'title' => $press->title,
                        'excerpt' => $this->excerpt($press->content),
                        'image' => $press->image ? '/storage/'.$press->image : null,
                        'url' => '/communiques/'.$press->slug,
                        'date' => optional($press->created_at)->toIso8601String(),
                        'sort_at' => optional($press->created_at)->timestamp ?? 0,
                    ]);
                });
        }

        if (Schema::hasTable('interviews')) {
            Interview::query()
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('interviewee_name', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('quote_highlight', 'like', $like)
                        ->orWhere('content', 'like', $like);
                })
                ->limit(200)
                ->get()
                ->each(function (Interview $interview) use ($results) {
                    $results->push([
                        'type' => 'interview',
                        'type_label' => 'Interview',
                        'title' => $interview->title,
                        'excerpt' => $this->excerpt($interview->excerpt ?: $interview->content),
                        'image' => $interview->hero_image ? '/storage/'.$interview->hero_image : null,
                        'url' => '/interviews/'.$interview->slug,
                        'date' => optional($interview->published_at)->toIso8601String(),
                        'sort_at' => optional($interview->published_at)->timestamp ?? 0,
                    ]);
                });
        }

        if (Schema::hasTable('players')) {
            Player::query()
                ->where(function ($q) use ($like) {
                    $q->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('position', 'like', $like)
                        ->orWhere('nationality', 'like', $like);
                })
                ->limit(200)
                ->get()
                ->each(function (Player $player) use ($results) {
                    $results->push([
                        'type' => 'player',
                        'type_label' => 'Effectif',
                        'title' => trim($player->first_name.' '.$player->last_name),
                        'excerpt' => trim(($player->position ?? '').' · '.($player->nationality ?? ''), ' ·'),
                        'image' => $player->photo ? '/storage/'.$player->photo : null,
                        'url' => '/teams',
                        'date' => null,
                        'sort_at' => 0,
                    ]);
                });
        }

        if (Schema::hasTable('coaches')) {
            Coach::query()
                ->where(function ($q) use ($like) {
                    $q->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->limit(200)
                ->get()
                ->each(function (Coach $coach) use ($results) {
                    $results->push([
                        'type' => 'coach',
                        'type_label' => 'Coach',
                        'title' => trim($coach->first_name.' '.$coach->last_name),
                        'excerpt' => $this->excerpt($coach->description),
                        'image' => $coach->photo ? '/storage/'.$coach->photo : null,
                        'url' => '/teams',
                        'date' => null,
                        'sort_at' => 0,
                    ]);
                });
        }

        if (Schema::hasTable('staff')) {
            Staff::query()
                ->where(function ($q) use ($like) {
                    $q->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('position', 'like', $like);
                })
                ->limit(200)
                ->get()
                ->each(function (Staff $member) use ($results) {
                    $results->push([
                        'type' => 'staff',
                        'type_label' => 'Staff',
                        'title' => trim($member->first_name.' '.$member->last_name),
                        'excerpt' => $member->position,
                        'image' => $member->photo ? '/storage/'.$member->photo : null,
                        'url' => '/teams',
                        'date' => null,
                        'sort_at' => 0,
                    ]);
                });
        }

        if (Schema::hasTable('galleries')) {
            Gallery::query()
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->limit(200)
                ->get()
                ->each(function (Gallery $gallery) use ($results) {
                    $results->push([
                        'type' => 'gallery',
                        'type_label' => 'Galerie',
                        'title' => $gallery->name,
                        'excerpt' => $this->excerpt($gallery->description),
                        'image' => $gallery->cover_image ? '/storage/'.$gallery->cover_image : null,
                        'url' => '/galleries/'.$gallery->id,
                        'date' => optional($gallery->created_at)->toIso8601String(),
                        'sort_at' => optional($gallery->created_at)->timestamp ?? 0,
                    ]);
                });
        }

        if (Schema::hasTable('videos')) {
            Video::query()
                ->where('title', 'like', $like)
                ->limit(200)
                ->get()
                ->each(function (Video $video) use ($results) {
                    $results->push([
                        'type' => 'video',
                        'type_label' => 'Vidéo',
                        'title' => $video->title,
                        'excerpt' => null,
                        'image' => null,
                        'url' => '/videos',
                        'date' => optional($video->created_at)->toIso8601String(),
                        'sort_at' => optional($video->created_at)->timestamp ?? 0,
                    ]);
                });
        }

        // Sort by recency, put timeless entries (players, coach, staff) after dated ones.
        $sorted = $results->sortByDesc('sort_at')->values();
        $sliced = $sorted->slice(($page - 1) * self::PER_PAGE, self::PER_PAGE)->values();

        // Remove internal sort key before serializing.
        $sliced = $sliced->map(function (array $row) {
            unset($row['sort_at']);
            return $row;
        });

        $paginator = new LengthAwarePaginator(
            $sliced,
            $sorted->count(),
            self::PER_PAGE,
            $page,
            [
                'path' => $request->url(),
                'query' => array_filter($request->query(), fn ($v, $k) => $k !== 'page', ARRAY_FILTER_USE_BOTH),
            ]
        );

        return Inertia::render('Search', [
            'query' => $query,
            'results' => $paginator,
        ]);
    }

    private function excerpt(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return null;
        }
        return Str::limit(trim(strip_tags($text)), 180);
    }

    private function emptyPaginator(Request $request, int $page): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            collect(),
            0,
            self::PER_PAGE,
            $page,
            [
                'path' => $request->url(),
                'query' => array_filter($request->query(), fn ($v, $k) => $k !== 'page', ARRAY_FILTER_USE_BOTH),
            ]
        );
    }
}
