<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\StoreDocumentRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(StoreDocumentRequest $request)
    {
        $file = $request->file('file');
        $path = $file->store('documents/'.Auth::id(), 'public');
        $name = $request->name ?? $file->getClientOriginalName();

        $document = Document::create([
            'user_id' => Auth::id(),
            'documentable_type' => $request->documentable_type,
            'documentable_id' => $request->documentable_id,
            'name' => $name,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->back()->with('status', 'Document uploaded.');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        Storage::disk('public')->delete($document->path);
        $document->delete();

        return redirect()->back()->with('status', 'Document deleted.');
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document);

        return Storage::disk('public')->download($document->path, $document->name);
    }
}
