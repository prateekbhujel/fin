<?php

namespace App\Http\Controllers\Modules\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Transactions\StoreTransactionRequest;
use App\Http\Requests\Modules\Transactions\UpdateTransactionRequest;
use App\Mail\TransactionCreatedMail;
use App\Models\Category;
use App\Models\Document;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index()
    {
        return view('modules.transactions.index', [
            'transactions' => Transaction::query()
                ->with(['category', 'user'])
                ->filter(request()->only([
                    'search',
                    'type',
                    'category_id',
                    'date_from',
                    'date_to',
                    'from_bs',
                    'to_bs',
                ]))
                ->latest('transaction_date')
                ->paginate(12)
                ->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
            'types' => config('finance.transaction_types'),
        ]);
    }

    public function create()
    {
        return view('modules.transactions.create', [
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
            'types' => config('finance.transaction_types'),
            'paymentMethods' => config('finance.payment_methods'),
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = $this->persistTransaction(new Transaction(), $request->validated());
        $this->storeDocuments($transaction, $request->file('documents', []));
        $this->sendNotificationIfEnabled($transaction);

        return redirect()->route('transactions.index')->with('status', 'Transaction recorded successfully.');
    }

    public function show(Transaction $transaction)
    {
        return view('modules.transactions.show', [
            'transaction' => $transaction->load(['category', 'user', 'documents']),
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('modules.transactions.edit', [
            'transaction' => $transaction->load('documents'),
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
            'types' => config('finance.transaction_types'),
            'paymentMethods' => config('finance.payment_methods'),
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction = $this->persistTransaction($transaction, $request->validated());
        $this->storeDocuments($transaction, $request->file('documents', []));

        return redirect()->route('transactions.index')->with('status', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        foreach ($transaction->documents as $document) {
            Storage::disk($document->disk)->delete($document->path);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('status', 'Transaction deleted successfully.');
    }

    protected function persistTransaction(Transaction $transaction, array $validated): Transaction
    {
        $category = isset($validated['category_id']) && $validated['category_id']
            ? Category::find($validated['category_id'])
            : null;

        if ($category && $category->type !== $validated['type']) {
            throw ValidationException::withMessages([
                'category_id' => 'Selected category does not match the transaction type.',
            ]);
        }

        $transaction->fill([
            'user_id' => $transaction->exists ? $transaction->user_id : auth()->id(),
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'transaction_bs' => $validated['transaction_date_bs'] ?: bs_date($validated['transaction_date']),
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ])->save();

        return $transaction->refresh();
    }

    protected function storeDocuments(Transaction $transaction, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store('documents', 'public');

            Document::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => basename($path),
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }

    protected function sendNotificationIfEnabled(Transaction $transaction): void
    {
        if (! setting_bool('mail.send_transaction_notifications', false)) {
            return;
        }

        $recipient = setting('mail.notification_email');

        if (! filled($recipient)) {
            return;
        }

        Mail::to($recipient)->send(new TransactionCreatedMail($transaction->load(['category', 'user'])));

        $transaction->update(['email_notified' => true]);
    }
}
