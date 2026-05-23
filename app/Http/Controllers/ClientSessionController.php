<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'photo'    => 'nullable|image|max:2048',
            'fields'   => 'array',
            'fields.*' => 'nullable|string',
        ]);

        $session = $client->sessions()->create([
            'photo_path'   => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
            'session_date' => Carbon::now('UTC'),
        ]);

        $template = $client->template;
        foreach ($template->fields as $field) {
            $session->fieldValues()->create([
                'template_field_id' => $field->id,
                'value'             => $request->input('fields.' . $field->id) ?? '',
            ]);
        }

        return redirect()->route('clients.show', $client)->with('success', 'Новый сеанс сохранён');
    }

    public function destroy($clientId, $sessionId)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($clientId);
        $session = $client->sessions()->findOrFail($sessionId);
        $session->delete();
        return back()->with('success', 'Сеанс удалён');
    }
}