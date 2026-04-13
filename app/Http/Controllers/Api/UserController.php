<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: '/api/v1/users',
        summary: 'List users',
        security: [['basicAuth' => []]],
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Search by name or email', schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'User list')
        ]
    )]
    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(20);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        abort(405);
    }

    #[OA\Get(
        path: '/api/v1/users/{user}',
        summary: 'Show a single user',
        security: [['basicAuth' => []]],
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'User detail'),
            new OA\Response(response: 404, description: 'User not found')
        ]
    )]
    public function show(User $user)
    {
        return response()->json($user->load('roles'));
    }

    public function update(Request $request, User $user)
    {
        abort(405);
    }

    public function destroy(User $user)
    {
        abort(405);
    }
}
