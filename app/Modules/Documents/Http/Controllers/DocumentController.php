<?php

namespace App\Modules\Documents\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Modules\Documents\DTOs\DocumentFiltersData;
use App\Modules\Documents\DTOs\DocumentUploadData;
use App\Modules\Documents\Http\Requests\StoreDocumentRequest;
use App\Modules\Documents\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        protected DocumentService $documents,
    ) {
    }

    public function index()
    {
        return view('modules.documents.index', [
            'documents' => $this->documents->paginate(DocumentFiltersData::fromArray(
                request()->only(['search', 'transaction_id'])
            )),
            'transactions' => $this->documents->recentTransactions(),
        ]);
    }

    public function create()
    {
        return redirect()->route('documents.index');
    }

    public function store(StoreDocumentRequest $request)
    {
        $this->documents->upload(
            data: DocumentUploadData::fromArray($request->validated()),
            files: $request->file('files', []),
            userId: (int) auth()->id(),
        );

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

    public function update(Request $request, Document $document)
    {
        return redirect()->route('documents.index');
    }

    public function destroy(Document $document)
    {
        $this->documents->delete($document);

        return redirect()->route('documents.index')->with('status', 'Document removed successfully.');
    }

    public function download(Document $document)
    {
        return $this->documents->download($document);
    }
}
