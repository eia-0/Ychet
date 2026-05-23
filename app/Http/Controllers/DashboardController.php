<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->clients()
            ->withCount('sessions')
            ->with('latestSession');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('template_id')) {
            $query->where('template_id', $request->template_id);
        }

        $clients = $query->orderBy('last_name')->paginate(10);
        $templates = auth()->user()->templates()->orderBy('name')->get();

        return view('dashboard', compact('clients', 'templates'));
    }
}