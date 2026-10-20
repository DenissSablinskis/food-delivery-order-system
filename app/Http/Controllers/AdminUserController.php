<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;

use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('pages.admin.users.index', compact('users'));
    }

    public function edit(User $user) {
        return view('pages.admin.users.edit', compact('user'));
    }

    public function update(User $user, UpdateUserRequest $request) {

        $data = $request->validated();

        $user->update($data);

        return redirect(route('admin.users.index'))->with('success', 'Lietotāja dati veiksmīgi atjaunināti!');
    }

    public function destroy(User $user) {

        if ($user->orders()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'Lietotāju nevar dzēst, jo tam ir nepabeigti pasūtījumi!');
        } elseif ($user->operatorOrders()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'Operatoru nevar dzēst, jo tam ir nepabeigti pasūtījumi!');
        } elseif ($user->deliveryOrders()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'Kurjeru nevar dzēst, jo tam ir nepabeigti pasūtījumi!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Lietotājs veiksmīgi dzēsts!');
    }
}
