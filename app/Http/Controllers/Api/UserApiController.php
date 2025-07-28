<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role'); // ex: ?role=ENSEIGNANT
        $users = User::when($role, fn($q) => $q->where('role', $role))->get();

        return response()->json($users);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt('0000');
        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}
