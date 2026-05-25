<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $states = State::orderBy('name')->get();
        return view('masters.states.index', compact('states'));
    }

    public function create()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        return view('masters.states.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'boolean'],
        ]);

        State::create(array_merge($data, ['status' => $request->boolean('status')]));

        return redirect()->route('states.index')->with('status', 'State created successfully.');
    }

    public function edit(State $state)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        return view('masters.states.edit', compact('state'));
    }

    public function update(Request $request, State $state)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'boolean'],
        ]);

        $state->update(array_merge($data, ['status' => $request->boolean('status')]));

        return redirect()->route('states.index')->with('status', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $state->delete();
        return redirect()->route('states.index')->with('status', 'State deleted.');
    }
}
