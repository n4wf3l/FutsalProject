<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class InterviewController extends Controller
{
    public const ROLES = [
        'Sélectionneur national',
        'Joueur équipe nationale',
        'Capitaine',
        'Entraîneur de club',
        'Président de club',
        'Journaliste',
        'Arbitre',
        'Ancien joueur',
        'Personnalité',
    ];

    // ————————————————— Public —————————————————
    public function publicIndex(Request $request)
    {
        $role = $request->string('role')->toString();
        $query = Interview::published()->latest('published_at');
        if ($role !== '') {
            $query->where('interviewee_role', $role);
        }

        return Inertia::render('Interviews/Index', [
            'interviews' => $query->paginate(9)->withQueryString(),
            'roles' => self::ROLES,
            'filter' => ['role' => $role],
        ]);
    }

    public function publicShow(string $slug)
    {
        $interview = Interview::published()->where('slug', $slug)->firstOrFail();

        $related = Interview::published()
            ->where('id', '!=', $interview->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return Inertia::render('Interviews/Show', [
            'interview' => $interview,
            'related' => $related,
        ]);
    }

    // ————————————————— Admin —————————————————
    public function index()
    {
        return Inertia::render('Admin/Interviews/Index', [
            'interviews' => Interview::latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Interviews/Form', [
            'interview' => null,
            'roles' => self::ROLES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data = $this->handleImages($request, $data);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['user_id'] = Auth::id();

        Interview::create($data);

        return redirect()->route('admin.interviews.index')->with('success', 'Interview créée.');
    }

    public function edit(Interview $interview)
    {
        return Inertia::render('Admin/Interviews/Form', [
            'interview' => $interview,
            'roles' => self::ROLES,
        ]);
    }

    public function update(Request $request, Interview $interview)
    {
        $data = $this->validated($request);
        $data = $this->handleImages($request, $data, $interview);

        if ($data['title'] !== $interview->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $interview->id);
        }

        $interview->update($data);

        return redirect()->route('admin.interviews.index')->with('success', 'Interview mise à jour.');
    }

    public function destroy(Interview $interview)
    {
        foreach (['hero_image', 'interviewee_photo'] as $img) {
            if ($interview->$img) {
                Storage::disk('public')->delete($interview->$img);
            }
        }
        $interview->delete();

        return redirect()->route('admin.interviews.index')->with('success', 'Interview supprimée.');
    }

    // ————————————————— Helpers —————————————————
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'interviewee_name' => 'required|string|max:255',
            'interviewee_role' => 'required|string|max:120',
            'interviewee_affiliation' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            'interviewee_photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'video_url' => 'nullable|url|max:500',
            'excerpt' => 'nullable|string|max:500',
            'quote_highlight' => 'nullable|string|max:500',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);
    }

    private function handleImages(Request $request, array $data, ?Interview $interview = null): array
    {
        foreach (['hero_image', 'interviewee_photo'] as $field) {
            if ($request->hasFile($field)) {
                if ($interview && $interview->$field) {
                    Storage::disk('public')->delete($interview->$field);
                }
                $data[$field] = $request->file($field)->store('interviews', 'public');
            } else {
                unset($data[$field]);
            }
        }
        return $data;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (
            Interview::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
