<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ImageCompressor;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view('clients.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id'  => 'required|exists:templates,id',
            'last_name'    => 'required|string|max:255',
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'photo_before' => 'nullable|image|max:10240',   // до 10 МБ
            'photo_after'  => 'nullable|image|max:10240',
            'fields'       => 'array',
            'fields.*'     => 'nullable|string',
        ]);

        // Проверяем, что шаблон принадлежит мастеру
        $template = auth()->user()->templates()->findOrFail($request->template_id);

        $client = auth()->user()->clients()->create(
            $request->only(['last_name', 'first_name', 'middle_name', 'phone']) +
            ['template_id' => $template->id]
        );

        // Сжатие и сохранение фото
        $compressor = new ImageCompressor();
        $photoBefore = $request->file('photo_before')
            ? $compressor->compressAndStore($request->file('photo_before'))
            : null;
        $photoAfter = $request->file('photo_after')
            ? $compressor->compressAndStore($request->file('photo_after'))
            : null;

        $session = $client->sessions()->create([
            'photo_before' => $photoBefore,
            'photo_after'  => $photoAfter,
            'session_date' => Carbon::now('UTC'),
        ]);

        // Сохраняем значения динамических полей выбранного шаблона
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
        if ($client->user_id !== auth()->id()) {
            abort(403);
        }

        $client->load([
            'sessions' => function ($query) {
                $query->orderBy('session_date', 'desc');
            },
            'sessions.fieldValues.templateField',
            'template',
        ]);

        return view('clients.show', compact('client'));
    }

    public function destroy(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403);
        }

        $client->delete(); // каскадное удаление сеансов и значений полей
        return redirect()->route('dashboard')->with('success', 'Клиент удалён');
    }
}