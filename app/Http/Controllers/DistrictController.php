<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $districts = District::with('state')->orderBy('name')->get();
        return view('masters.districts.index', compact('districts'));
    }

    public function create()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $states = State::orderBy('name')->get();
        return view('masters.districts.create', compact('states'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'boolean'],
        ]);

        District::create(array_merge($data, ['status' => $request->boolean('status')]));

        return redirect()->route('districts.index')->with('status', 'District created successfully.');
    }

    public function edit(District $district)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $states = State::orderBy('name')->get();
        return view('masters.districts.edit', compact('district', 'states'));
    }

    public function update(Request $request, District $district)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'boolean'],
        ]);

        $district->update(array_merge($data, ['status' => $request->boolean('status')]));

        return redirect()->route('districts.index')->with('status', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $district->delete();
        return redirect()->route('districts.index')->with('status', 'District deleted.');
    }
}
