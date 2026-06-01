<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\InstrumentType;
use App\Models\User;
use Illuminate\Http\Request;

class InstrumentTypeController extends Controller
{
    private function isAdmin(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user instanceof User && $user->isAdmin();
    }

    public function index()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $instrumentTypes = InstrumentType::with('instrument')->orderBy('name')->get();

        return view('admin.instrument_types.index', compact('instrumentTypes'));
    }

    public function create()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $instruments = Instrument::orderBy('name')->get();

        return view('admin.instrument_types.create', compact('instruments'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'instrument_id' => ['required', 'exists:instruments,id'],
            'name' => ['required', 'string', 'max:100', 'unique:instrument_types,name'],
            'code' => ['nullable', 'string', 'max:50', 'unique:instrument_types,code'],
        ]);

        InstrumentType::create($validated);

        return redirect()->route('admin.instrument-types.index')->with('status', 'Instrument type created successfully.');
    }

    public function edit(InstrumentType $instrumentType)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $instruments = Instrument::orderBy('name')->get();

        return view('admin.instrument_types.edit', compact('instrumentType', 'instruments'));
    }

    public function update(Request $request, InstrumentType $instrumentType)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'instrument_id' => ['required', 'exists:instruments,id'],
            'name' => ['required', 'string', 'max:100', 'unique:instrument_types,name,' . $instrumentType->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:instrument_types,code,' . $instrumentType->id],
        ]);

        $instrumentType->update($validated);

        return redirect()->route('admin.instrument-types.index')->with('status', 'Instrument type updated successfully.');
    }

    public function destroy(InstrumentType $instrumentType)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $instrumentType->delete();

        return redirect()->route('admin.instrument-types.index')->with('status', 'Instrument type deleted successfully.');
    }
}
