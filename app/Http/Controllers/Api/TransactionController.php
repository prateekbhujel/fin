<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

class TransactionController extends Controller
{
    #[OA\Get(
        path: '/api/v1/transactions',
        summary: 'List transactions',
        security: [['basicAuth' => []]],
        tags: ['Transactions'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'type', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'category_id', in: 'query', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'date_from', in: 'query', schema: new OA\Schema(type: 'string', format: 'date')),
            new OA\Parameter(name: 'date_to', in: 'query', schema: new OA\Schema(type: 'string', format: 'date')),
            new OA\Parameter(name: 'from_bs', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'to_bs', in: 'query', schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Transaction list')
        ]
    )]
    public function index()
    {
        $transactions = Transaction::query()
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
            ->paginate(20);

        return response()->json($transactions);
    }

    #[OA\Post(
        path: '/api/v1/transactions',
        summary: 'Create a transaction',
        security: [['basicAuth' => []]],
        tags: ['Transactions'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'type', 'amount', 'transaction_date', 'payment_method'],
                properties: [
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'type', type: 'string', enum: ['income', 'expense']),
                    new OA\Property(property: 'category_id', type: 'integer', nullable: true),
                    new OA\Property(property: 'amount', type: 'number', format: 'float'),
                    new OA\Property(property: 'transaction_date', type: 'string', format: 'date'),
                    new OA\Property(property: 'payment_method', type: 'string'),
                    new OA\Property(property: 'reference_no', type: 'string', nullable: true),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Transaction created'),
            new OA\Response(response: 422, description: 'Validation failed')
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(array_keys(config('finance.transaction_types')))],
            'category_id' => ['nullable', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'payment_method' => ['required', Rule::in(array_keys(config('finance.payment_methods')))],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $category = isset($validated['category_id']) ? Category::find($validated['category_id']) : null;

        if ($category && $category->type !== $validated['type']) {
            return response()->json([
                'message' => 'Selected category does not match transaction type.',
            ], 422);
        }

        $transaction = Transaction::create([
            ...$validated,
            'user_id' => auth()->id(),
            'transaction_bs' => bs_date($validated['transaction_date']),
        ]);

        return response()->json($transaction->load(['category', 'user']), 201);
    }

    #[OA\Get(
        path: '/api/v1/transactions/{transaction}',
        summary: 'Show a single transaction',
        security: [['basicAuth' => []]],
        tags: ['Transactions'],
        parameters: [
            new OA\Parameter(name: 'transaction', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Transaction detail'),
            new OA\Response(response: 404, description: 'Transaction not found')
        ]
    )]
    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load(['category', 'user', 'documents']));
    }

    public function update(Request $request, Transaction $transaction)
    {
        abort(405);
    }

    public function destroy(Transaction $transaction)
    {
        abort(405);
    }
}
