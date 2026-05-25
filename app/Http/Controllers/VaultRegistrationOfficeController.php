<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\VaultRegistrationOffice;
use Illuminate\Http\Request;

class VaultRegistrationOfficeController extends Controller
{
    public function index()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $offices = VaultRegistrationOffice::with('district.state')->orderBy('office_name')->get();
        return view('masters.offices.index', compact('offices'));
    }

    public function create()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $districts = District::with('state')->orderBy('name')->get();
        return view('masters.offices.create', compact('districts'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'office_name' => ['required', 'string', 'max:255'],
            'office_code' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ]);

        VaultRegistrationOffice::create(array_merge($data, ['status' => $request->boolean('status')]));

        return redirect()->route('offices.index')->with('status', 'Registration office created successfully.');
    }

    public function edit(VaultRegistrationOffice $office)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $districts = District::with('state')->orderBy('name')->get();
        return view('masters.offices.edit', compact('office', 'districts'));
    }

    public function update(Request $request, VaultRegistrationOffice $office)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'office_name' => ['required', 'string', 'max:255'],
            'office_code' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ]);

        $office->update(array_merge($data, ['status' => $request->boolean('status')]));

        return redirect()->route('offices.index')->with('status', 'Registration office updated successfully.');
    }

    public function destroy(VaultRegistrationOffice $office)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $office->delete();
        return redirect()->route('offices.index')->with('status', 'Registration office deleted.');
    }
}
