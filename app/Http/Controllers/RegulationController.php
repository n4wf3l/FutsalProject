<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RegulationController extends Controller
{
    // Public "Le club" page (regulations + about sections)
    public function publicIndex()
    {
        SeoMeta::share(
            'Le club — Dina Kenitra FC',
            'L\'histoire de Dina Kenitra Futsal Club depuis 2011. Fondation, philosophie, palmarès, salle Al Wahda et documents officiels.'
        );

        return Inertia::render('About', [
            'regulations' => Regulation::orderBy('id', 'desc')->get(),
            'sections' => \App\Models\AboutSection::orderBy('id')->get(),
        ]);
    }

    public function index()
    {
        return Inertia::render('Admin/Regulations/Index', [
            'regulations' => Regulation::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Regulations/Form', [
            'regulation' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf|max:8192',
        ]);

        $pdfPath = $request->file('pdf')->store('regulations', 'public');

        Regulation::create([
            'title' => $data['title'],
            'pdf_path' => $pdfPath,
        ]);

        return redirect()->route('regulations.index')->with('success', 'Règlementation ajoutée.');
    }

    public function edit(Regulation $regulation)
    {
        return Inertia::render('Admin/Regulations/Form', [
            'regulation' => $regulation,
        ]);
    }

    public function update(Request $request, Regulation $regulation)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'pdf' => 'nullable|file|mimes:pdf|max:8192',
        ]);

        $update = ['title' => $data['title']];

        if ($request->hasFile('pdf')) {
            if ($regulation->pdf_path) {
                Storage::disk('public')->delete($regulation->pdf_path);
            }
            $update['pdf_path'] = $request->file('pdf')->store('regulations', 'public');
        }

        $regulation->update($update);

        return redirect()->route('regulations.index')->with('success', 'Règlementation mise à jour.');
    }

    public function destroy(Regulation $regulation)
    {
        if ($regulation->pdf_path) {
            Storage::disk('public')->delete($regulation->pdf_path);
        }

        $regulation->delete();

        return redirect()->route('regulations.index')->with('success', 'Règlementation supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:regulations,id',
        ]);

        $regulations = Regulation::whereIn('id', $data['ids'])->get();
        foreach ($regulations as $regulation) {
            if ($regulation->pdf_path) {
                Storage::disk('public')->delete($regulation->pdf_path);
            }
            $regulation->delete();
        }

        return redirect()->route('regulations.index')->with('success', count($regulations) . ' règlementation(s) supprimée(s).');
    }
}
