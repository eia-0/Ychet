<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = auth()->user()->templates()->withCount('fields')->orderBy('name')->get();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        if (auth()->user()->templates()->count() >= 10) {
            return redirect()->route('templates.index')->with('error', 'Достигнут лимит в 10 шаблонов.');
        }
        return view('templates.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->templates()->count() >= 10) {
            return back()->with('error', 'Лимит шаблонов исчерпан.');
        }
        $request->validate(['name' => 'required|string|max:255']);
        auth()->user()->templates()->create($request->only('name'));
        return redirect()->route('templates.index')->with('success', 'Шаблон создан');
    }

    public function show(Template $template)
    {
        $this->authorizeTemplate($template);
        $template->load('fields');
        return view('templates.show', compact('template'));
    }

    public function edit(Template $template)
    {
        $this->authorizeTemplate($template);
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $this->authorizeTemplate($template);
        $request->validate(['name' => 'required|string|max:255']);
        $template->update($request->only('name'));
        return redirect()->route('templates.index')->with('success', 'Шаблон обновлён');
    }

    public function destroy(Template $template)
    {
        $this->authorizeTemplate($template);
        if ($template->clients()->exists()) {
            return back()->with('error', 'Нельзя удалить шаблон, к которому привязаны клиенты.');
        }
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Шаблон удалён');
    }

    // Добавление поля в шаблон
    public function addField(Request $request, Template $template)
    {
        $this->authorizeTemplate($template);
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|in:text,textarea',
        ]);
        $template->fields()->create([
            'name' => $request->name,
            'type' => $request->type ?? 'text',
            'sort_order' => $request->sort_order ?? 0,
        ]);
        return back()->with('success', 'Поле добавлено');
    }

    // Удаление поля из шаблона
    public function removeField(Template $template, $fieldId)
    {
        $this->authorizeTemplate($template);
        $field = $template->fields()->findOrFail($fieldId);
        $field->delete();
        return back()->with('success', 'Поле удалено');
    }

    private function authorizeTemplate(Template $template)
    {
        if ($template->user_id !== auth()->id()) {
            abort(403);
        }
    }
    public function getFields(Template $template)
    {
        $this->authorizeTemplate($template);
        $fields = $template->fields()->orderBy('sort_order')->get();
        return view('templates._fields_list', compact('fields'))->render();
    }
}