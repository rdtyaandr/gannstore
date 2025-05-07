<?php

namespace App\Http\Controllers;

use App\Models\StrukField;
use Illuminate\Http\Request;

class StrukFieldController extends Controller
{
    public function index()
    {
        $fields = StrukField::orderBy('order')->get();
        return view('struk-fields.index', compact('fields'));
    }

    public function create()
    {
        return view('struk-fields.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:struk_fields',
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,date,textarea',
            'is_required' => 'boolean',
            'order' => 'required|integer|min:0'
        ]);

        StrukField::create($request->all());

        return redirect()->route('struk-fields.index')
            ->with('success', 'Field berhasil ditambahkan.');
    }

    public function edit(StrukField $strukField)
    {
        return view('struk-fields.edit', compact('strukField'));
    }

    public function update(Request $request, StrukField $strukField)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:struk_fields,name,' . $strukField->id,
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,date,textarea',
            'is_required' => 'boolean',
            'order' => 'required|integer|min:0'
        ]);

        $strukField->update($request->all());

        return redirect()->route('struk-fields.index')
            ->with('success', 'Field berhasil diperbarui.');
    }

    public function destroy(StrukField $strukField)
    {
        $strukField->delete();

        return redirect()->route('struk-fields.index')
            ->with('success', 'Field berhasil dihapus.');
    }
} 