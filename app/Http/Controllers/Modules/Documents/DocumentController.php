<?php

namespace App\Http\Controllers\Modules\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Documents\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $query = Document::query()->with(['transaction', 'user'])->latest();

        if ($search = request('search')) {
            $query->where('original_name', 'like', "%{$search}%");
        }

        if ($transactionId = request('transaction_id')) {
            $query->where('transaction_id', $transactionId);
        }

        return view('modules.documents.index', [
            'documents' => $query->paginate(12)->withQueryString(),
            'transactions' => Transaction::query()->latest('transaction_date')->take(50)->get(),
        ]);
    }

    public function create()
    {
        return redirect()->route('documents.index');
    }

    public function store(StoreDocumentRequest $request)
    {
        foreach ($request->file('files', []) as $file) {
            $path = $file->store('documents', 'public');

            Document::create([
                'transaction_id' => $request->integer('transaction_id') ?: null,
                'user_id' => auth()->id(),
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => basename($path),
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);
        }

        return redirect()->route('documents.index')->with('status', 'Documents uploaded successfully.');
    }

    public function show(Document $document)
    {
        return $this->download($document);
    }

    public function edit(Document $document)
    {
        return redirect()->route('documents.index');
    }

    public function update(\Illuminate\Http\Request $request, Document $document)
    {
        return redirect()->route('documents.index');
    }

    public function destroy(Document $document)
    {
        Storage::disk($document->disk)->delete($document->path);
        $document->delete();

        return redirect()->route('documents.index')->with('status', 'Document removed successfully.');
    }

    public function download(Document $document)
    {
        return Storage::disk($document->disk)->download($document->path, $document->original_name);
    }
}
