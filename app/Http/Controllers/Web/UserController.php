<?php

namespace App\Http\Controllers\Web;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('web.users.index', [
            'users' => User::query()->with('warehouse')->latest()->paginate(20),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'roles' => array_column(UserRole::cases(), 'value'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Usuario criado.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return back()->with('success', 'Usuario atualizado.');
    }

    public function destroy(User $user)
    {
        if ((int) auth()->id() === (int) $user->id) {
            return back()->with('error', 'Nao pode apagar seu proprio usuario.');
        }

        $user->delete();

        return back()->with('success', 'Usuario removido.');
    }
}
