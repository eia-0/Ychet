<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientSession;
use App\Services\ImageCompressor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientSessionController extends Controller
{
    public function create($clientId)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($clientId);
        $template = $client->template;
        $templateFields = $template->fields()->orderBy('sort_order')->get();
        $lastSession = $client->latestSession;

        $values = [];
        if ($lastSession) {
            $values = $lastSession->fieldValues->pluck('value', 'template_field_id')->toArray();
        }

        return view('clients.sessions.create', compact('client', 'templateFields', 'values'));
    }

    public function store(Request $request, $clientId)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($clientId);

        $request->validate([
            'photo_before' => 'nullable|image|max:10240',
            'photo_after'  => 'nullable|image|max:10240',
            'fields'       => 'array',
            'fields.*'     => 'nullable|string',
        ]);

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

        $template = $client->template;
        foreach ($template->fields as $field) {
            $value = $request->input('fields.' . $field->id, ''); // значение или пустая строка
            $session->fieldValues()->create([
                'template_field_id' => $field->id,
                'value'             => $value,
            ]);
        }

        return redirect()->route('clients.show', $client)->with('success', 'Новый сеанс сохранён');
    }

    public function edit($clientId, $sessionId)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($clientId);
        $session = $client->sessions()->findOrFail($sessionId);
        $template = $client->template;
        $templateFields = $template->fields()->orderBy('sort_order')->get();

        $values = $session->fieldValues->pluck('value', 'template_field_id')->toArray();

        return view('clients.sessions.edit', compact('client', 'session', 'templateFields', 'values'));
    }

    public function update(Request $request, $clientId, $sessionId)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($clientId);
        $session = $client->sessions()->findOrFail($sessionId);

        $request->validate([
            'photo_before' => 'nullable|image|max:10240',
            'photo_after'  => 'nullable|image|max:10240',
            'fields'       => 'array',
            'fields.*'     => 'nullable|string',
        ]);

        $compressor = new ImageCompressor();

        if ($request->hasFile('photo_before')) {
            if ($session->photo_before) {
                \Storage::disk('public')->delete($session->photo_before);
            }
            $session->photo_before = $compressor->compressAndStore($request->file('photo_before'));
        }

        if ($request->hasFile('photo_after')) {
            if ($session->photo_after) {
                \Storage::disk('public')->delete($session->photo_after);
            }
            $session->photo_after = $compressor->compressAndStore($request->file('photo_after'));
        }

        $session->save();

        // Обновляем значения полей
        $template = $client->template;
        foreach ($template->fields as $field) {
            $fieldValue = $session->fieldValues()->where('template_field_id', $field->id)->first();
            if ($fieldValue) {
                $fieldValue->update(['value' => $request->input('fields.' . $field->id) ?? '']);
            } else {
                $session->fieldValues()->create([
                    'template_field_id' => $field->id,
                    'value'             => $request->input('fields.' . $field->id) ?? '',
                ]);
            }
        }

        return redirect()->route('clients.show', $client)->with('success', 'Сеанс обновлён');
    }

    public function destroy($clientId, $sessionId)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($clientId);
        $session = $client->sessions()->findOrFail($sessionId);
        $session->delete();
        return back()->with('success', 'Сеанс удалён');
    }
}