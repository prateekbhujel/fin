<?php

namespace App\Modules\Transactions\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Modules\Transactions\DTOs\TransactionData;
use App\Modules\Transactions\DTOs\TransactionFiltersData;
use App\Modules\Transactions\Http\Requests\StoreTransactionRequest;
use App\Modules\Transactions\Http\Requests\UpdateTransactionRequest;
use App\Modules\Transactions\Services\TransactionService;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactions,
    ) {
    }

    public function index()
    {
        $filters = TransactionFiltersData::fromArray(request()->only([
            'search',
            'type',
            'category_id',
            'date_from',
            'date_to',
            'from_bs',
            'to_bs',
        ]));

        return view('modules.transactions.index', [
            'transactions' => $this->transactions->paginate($filters),
            'categories' => $this->transactions->categories(),
            'types' => $this->transactions->types(),
        ]);
    }

    public function create()
    {
        return view('modules.transactions.create', [
            'categories' => $this->transactions->categories(onlyActive: true),
            'types' => $this->transactions->types(),
            'paymentMethods' => $this->transactions->paymentMethods(),
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        $this->transactions->store(
            data: TransactionData::fromArray($request->validated()),
            ownerUserId: (int) auth()->id(),
            actorUserId: (int) auth()->id(),
            documents: $request->file('documents', []),
        );

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
            'categories' => $this->transactions->categories(onlyActive: true),
            'types' => $this->transactions->types(),
            'paymentMethods' => $this->transactions->paymentMethods(),
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->transactions->update(
            transaction: $transaction,
            data: TransactionData::fromArray($request->validated()),
            actorUserId: (int) auth()->id(),
            documents: $request->file('documents', []),
        );

        return redirect()->route('transactions.index')->with('status', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->transactions->delete($transaction);

        return redirect()->route('transactions.index')->with('status', 'Transaction deleted successfully.');
    }
}
