<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $roleFilter = $request->get('role');
        $users = User::when($roleFilter, fn($q) => $q->where('role', $roleFilter))->get();

        return view('user.index', [
            'users' => $users,
            'roleFilter' => $roleFilter,
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt('0000');
        User::create($request->validated());
        return redirect()->back()->with('success', 'Utilisateur ajouté avec succès');
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->back()->with('success', 'Utilisateur modifié avec succès');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé');
    }
}
