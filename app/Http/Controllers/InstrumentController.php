<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Http\Request;

class InstrumentController extends Controller
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

        $instruments = Instrument::orderBy('name')->get();

        return view('admin.instruments.index', compact('instruments'));
    }

    public function create()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return view('admin.instruments.create');
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
            'name' => ['required', 'string', 'max:100', 'unique:instruments,name'],
            'code' => ['nullable', 'string', 'max:50', 'unique:instruments,code'],
        ]);

        Instrument::create($validated);

        return redirect()->route('admin.instruments.index')->with('status', 'Instrument created successfully.');
    }

    public function edit(Instrument $instrument)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return view('admin.instruments.edit', compact('instrument'));
    }

    public function update(Request $request, Instrument $instrument)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:instruments,name,' . $instrument->id],
            'code' => ['nullable', 'string', 'max:50', 'unique:instruments,code,' . $instrument->id],
        ]);

        $instrument->update($validated);

        return redirect()->route('admin.instruments.index')->with('status', 'Instrument updated successfully.');
    }

    public function destroy(Instrument $instrument)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if (! $this->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $instrument->delete();

        return redirect()->route('admin.instruments.index')->with('status', 'Instrument deleted successfully.');
    }
}
