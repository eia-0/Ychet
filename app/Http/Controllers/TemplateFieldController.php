<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateFieldController extends Controller
{
    public function index()
    {
        $fields = auth()->user()->templateFields()->orderBy('sort_order')->get();
        return view('template-fields.index', compact('fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|in:text,textarea',
            'sort_order' => 'nullable|integer',
        ]);

        auth()->user()->templateFields()->create([
            'name' => $request->name,
            'type' => $request->type ?? 'text',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('template-fields.index')->with('success', 'Поле добавлено');
    }

    public function update(Request $request, $id)
    {
        $field = auth()->user()->templateFields()->findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $field->update($request->only('name', 'type', 'sort_order'));
        return back()->with('success', 'Поле обновлено');
    }

    public function destroy($id)
    {
        $field = auth()->user()->templateFields()->findOrFail($id);
        $field->delete();
        return back()->with('success', 'Поле удалено');
    }

    // create и edit можно не реализовывать (для модальных окон или простых форм), оставлю минимально
    public function create() { return view('template-fields.create'); }
    public function edit($id) { $field = auth()->user()->templateFields()->findOrFail($id); return view('template-fields.edit', compact('field')); }
}