<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->withCount('clients')
            ->withCount(['clients as sessions_count' => function ($query) {
                $query->join('client_sessions', 'clients.id', '=', 'client_sessions.client_id');
            }])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.index', compact('users'));
    }

    public function show(User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $clients = $user->clients()
            ->with(['sessions' => function ($query) {
                $query->orderBy('session_date', 'desc');
            }, 'sessions.fieldValues.templateField'])
            ->orderBy('last_name')
            ->paginate(10);

        return view('admin.show', compact('user', 'clients'));
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Нельзя удалить администратора');
        }

        $user->delete();

        return back()->with('success', 'Мастер удалён');
    }
}