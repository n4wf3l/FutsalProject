<?php
namespace App\Http\Controllers;

use App\Models\PressRelease;
use Illuminate\Http\Request;

class PressReleaseController extends Controller
{
    public function index()
    {
        $pressReleases = PressRelease::all();
        return view('press-releases.index', compact('pressReleases'));
    }

    public function create()
    {
        return view('press-releases.create');
    }

    public function store(Request $request)
    {
        $pressRelease = new PressRelease();
        $pressRelease->title = $request->input('title');
        $pressRelease->content = $request->input('content');
        $pressRelease->date = $request->input('date');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time(). '.'. $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $pressRelease->image = $imageName;
        }
        $pressRelease->save();
        return redirect()->route('press-releases.index');
    }

    public function show($id)
    {
        $pressRelease = PressRelease::find($id);
        return view('press-releases.show', compact('pressRelease'));
    }

    public function edit($id)
    {
        $pressRelease = PressRelease::find($id);
        return view('press-releases.edit', compact('pressRelease'));
    }

    public function update(Request $request, $id)
    {
        $pressRelease = PressRelease::find($id);
        $pressRelease->title = $request->input('title');
        $pressRelease->content = $request->input('content');
        $pressRelease->date = $request->input('date');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time(). '.'. $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $pressRelease->image = $imageName;
        }
        $pressRelease->save();
        return redirect()->route('press-releases.index');
    }

    public function destroy($id)
    {
        $pressRelease = PressRelease::find($id);
        $pressRelease->delete();
        return redirect()->route('press-releases.index');
    }
}
		