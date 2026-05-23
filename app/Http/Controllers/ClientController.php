<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function create()
    {
        $templates = auth()->user()->templates()->orderBy('name')->get();
        if ($templates->isEmpty()) {
            return redirect()->route('templates.create')->with('info', 'Сначала создайте хотя бы один шаблон.');
        }
        // Передаём шаблоны в представление, где будет выпадающий список и динамическая подгрузка полей
        return view('clients.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'last_name'  => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name'=> 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'photo'      => 'nullable|image|max:2048',
            'fields'     => 'array',
            'fields.*'   => 'nullable|string',
        ]);

        // Убедимся, что шаблон принадлежит текущему мастеру
        $template = auth()->user()->templates()->findOrFail($request->template_id);

        $client = auth()->user()->clients()->create(
            $request->only(['last_name', 'first_name', 'middle_name', 'phone']) + ['template_id' => $template->id]
        );

        $session = $client->sessions()->create([
            'photo_path'   => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
            'session_date' => Carbon::now('UTC'),
        ]);

        // Сохраняем значения полей выбранного шаблона
        foreach ($template->fields as $field) {
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
        }, 'sessions.fieldValues.templateField', 'template']);

        return view('clients.show', compact('client'));
    }

    public function destroy(Client $client)
    {
        if ($client->user_id !== auth()->id()) abort(403);
        $client->delete();
        return redirect()->route('dashboard')->with('success', 'Клиент удалён');
    }
}