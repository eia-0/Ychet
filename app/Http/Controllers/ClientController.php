<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function create()
    {
        $templateFields = auth()->user()->templateFields()->orderBy('sort_order')->get();
        return view('clients.create', compact('templateFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name'  => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name'=> 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'photo'      => 'nullable|image|max:2048',
            'fields'     => 'array',
            'fields.*'   => 'nullable|string',
        ]);

        $client = auth()->user()->clients()->create(
            $request->only(['last_name', 'first_name', 'middle_name', 'phone'])
        );

        $session = $client->sessions()->create([
            'photo_path'   => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
            'session_date' => now(),
        ]);

        $templateFields = auth()->user()->templateFields;
        foreach ($templateFields as $field) {
            $session->fieldValues()->create([
                'template_field_id' => $field->id,
                'value'             => $request->input('fields.' . $field->id) ?? '',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Клиент и первый сеанс добавлены');
    }

    public function show(Client $client)
    {
        if ($client->user_id !== auth()->id()) abort(403);

        $client->load(['sessions' => function ($query) {
            $query->orderBy('session_date', 'desc');
        }, 'sessions.fieldValues.templateField']);

        return view('clients.show', compact('client'));
    }

    // НОВЫЙ МЕТОД
    public function destroy(Client $client)
    {
        if ($client->user_id !== auth()->id()) abort(403);

        $client->delete(); // каскадно удалятся сеансы и значения полей

        return redirect()->route('dashboard')->with('success', 'Клиент удалён');
    }
}