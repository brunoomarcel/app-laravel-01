<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index() {

        $users = User::paginate(10); //User::all();

        return view('admin.users.index', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request) {
        User::create($request->validated());

        return redirect()->route('users.index')->with('success', 'Usuario criado com sucesso!');
    }

    public function edit(string $id) {
       
        if(!$user = User::find($id)) {
            return redirect()->route('users.index')->with('message', 'Usuário não encontrado!');
        }

        return view('admin.users.edit', compact('user'));
    }

    function update(UpdateUserRequest $request, string $id) {

        if(!$user = User::find($id)) {
            return back()->with('message', 'Usuário não encontrado!');
        }

        $data = $request->only([
            'name',
            'email',
        ]);
        if($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário editado com sucesso!');
    }
}
