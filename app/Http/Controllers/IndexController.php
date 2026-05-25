<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Index;
use App\Models\State;
use App\Models\VaultRegistrationOffice;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $status = trim((string) $request->input('status'));
        $stateId = $request->input('state_id');
        $districtId = $request->input('district_id');
        $officeId = $request->input('office_id');
        $volumeYear = trim((string) $request->input('volume_year'));
        $bookNumber = trim((string) $request->input('book_number'));
        $volumeNumber = trim((string) $request->input('volume_number'));

        $query = Index::with(['state', 'district', 'office'])->orderByDesc('created_at');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($stateId !== null && $stateId !== '') {
            $query->where('state_id', $stateId);
        }

        if ($districtId !== null && $districtId !== '') {
            $query->where('district_id', $districtId);
        }

        if ($officeId !== null && $officeId !== '') {
            $query->where('vault_registration_office_id', $officeId);
        }

        if ($volumeYear !== '') {
            $query->where('volume_year', 'like', '%' . $volumeYear . '%');
        }

        if ($bookNumber !== '') {
            $query->where('book_number', 'like', '%' . $bookNumber . '%');
        }

        if ($volumeNumber !== '') {
            $query->where('volume_number', 'like', '%' . $volumeNumber . '%');
        }

        $indexes = $query->get();

        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $offices = VaultRegistrationOffice::orderBy('office_name')->get();

        return view('indexes.index', compact('indexes', 'states', 'districts', 'offices', 'status', 'stateId', 'districtId', 'officeId', 'volumeYear', 'bookNumber', 'volumeNumber'));
    }

    public function create()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $offices = VaultRegistrationOffice::orderBy('office_name')->get();

        return view('indexes.create', compact('states', 'districts', 'offices'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'vault_registration_office_id' => ['required', 'exists:vault_registration_offices,id'],
            'volume_year' => ['required', 'string', 'max:10'],
            'book_number' => ['required', 'string', 'max:100'],
            'volume_number' => ['required', 'string', 'max:100'],
            'is_volume_forwarded' => ['nullable', 'boolean'],
        ]);

        Index::create(array_merge($data, [
            'is_volume_forwarded' => $request->boolean('is_volume_forwarded'),
            'status' => 'pending',
            'locked' => false,
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('indexes.index')->with('status', 'Index created successfully.');
    }

    public function edit(Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if ($index->locked) {
            return redirect()->route('indexes.index')->with('error', 'Verified index cannot be edited.');
        }

        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $offices = VaultRegistrationOffice::orderBy('office_name')->get();

        return view('indexes.edit', compact('index', 'states', 'districts', 'offices'));
    }

    public function update(Request $request, Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if ($index->locked) {
            return redirect()->route('indexes.index')->with('error', 'Verified index cannot be updated.');
        }

        $data = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'vault_registration_office_id' => ['required', 'exists:vault_registration_offices,id'],
            'volume_year' => ['required', 'string', 'max:10'],
            'book_number' => ['required', 'string', 'max:100'],
            'volume_number' => ['required', 'string', 'max:100'],
            'is_volume_forwarded' => ['nullable', 'boolean'],
        ]);

        $index->update(array_merge($data, ['is_volume_forwarded' => $request->boolean('is_volume_forwarded')]));

        return redirect()->route('indexes.index')->with('status', 'Index updated successfully.');
    }

    public function destroy(Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        if ($index->locked) {
            return redirect()->route('indexes.index')->with('error', 'Verified index cannot be deleted.');
        }

        $index->delete();
        return redirect()->route('indexes.index')->with('status', 'Index deleted successfully.');
    }
}
