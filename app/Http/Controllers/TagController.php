<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\Tag\StoreTagRequest;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount(['jobApplications', 'companies', 'contacts'])->where('user_id', Auth::id())->orderBy('name')->get();
        return view('tag.index', compact('tags'));
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create([
            'name' => $request->name,
            'color' => $request->color,
            'user_id' => Auth::id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json($tag);
        }

        return redirect()->back()->with('status', 'Tag created.');
    }

    public function update(StoreTagRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        $tag->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($tag);
        }

        return redirect()->back()->with('status', 'Tag updated.');
    }

    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        $tag->loadCount(['jobApplications', 'companies', 'contacts']);
        $tag->load([
            'jobApplications' => fn($q) => $q->with('company')->latest(),
            'companies',
            'contacts' => fn($q) => $q->with('company'),
        ]);

        return view('tag.show', compact('tag'));
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);
        $tag->delete();

        return redirect()->route('tags.index')->with('status', 'Tag deleted.');
    }
}
