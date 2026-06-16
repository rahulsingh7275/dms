<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Index;
use App\Models\IndexVerification;
use App\Models\State;
use App\Models\VaultRegistrationOffice;
use DateTime;
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

        // Prevent editing approved indexes
        if ($index->status === 'approved') {
            return redirect()->route('indexes.index')->with('error', 'Cannot edit an approved index.');
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

        // Prevent updating approved indexes
        if ($index->status === 'approved') {
            return redirect()->route('indexes.index')->with('error', 'Cannot update an approved index.');
        }

        $data = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'vault_registration_office_id' => ['required', 'exists:vault_registration_offices,id'],
            'volume_year' => ['required', 'string', 'max:10'],
            'book_number' => ['required', 'string', 'max:100'],
            'volume_number' => ['required', 'string', 'max:100'],
            'status_comment' => ['required', 'string', 'max:1000'],
            'is_volume_forwarded' => ['nullable', 'boolean'],
        ]);

        $data['updated_at'] = now();
        $data['is_volume_forwarded']=$request->boolean('is_volume_forwarded');
        $data['status']='pending';

        // dd($data);

        $index->update($data);

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

        // Prevent deleting approved indexes
        if ($index->status === 'approved') {
            return redirect()->route('indexes.index')->with('error', 'Cannot delete an approved index.');
        }

        $index->delete();
        return redirect()->route('indexes.index')->with('status', 'Index deleted successfully.');
    }

    public function show(Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $index->load(['state', 'district', 'office', 'deeds.scannedDocuments', 'deeds.deedVerifications', 'indexVerifications']);
        return view('indexes.show', compact('index'));
    }

    public function updateStatus(Request $request, Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $user = auth()->user();
        if (! $user || ! $user->isChecker()) {
            return redirect()->route('indexes.show', $index)->with('error', 'You do not have permission to change status.');
        }

        if (in_array($index->status, ['approved', 'rejected'])) {
            return redirect()->route('indexes.show', $index)->with('error', 'Status cannot be changed after approval or rejection.');
        }

        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['status'] === 'rejected' && empty(trim($data['comment'] ?? ''))) {
            return redirect()->route('indexes.show', $index)->with('error', 'Please provide a comment when rejecting.');
        }

        // Record verification in index_verifications table instead of writing to a non-existing column
        IndexVerification::create([
            'index_id' => $index->id,
            'checker_id' => auth()->id(),
            'status' => $data['status'],
            'remarks' => $data['comment'] ?? null,
            'verified_at' => now(),
        ]);

        $index->status = $data['status'];
        $index->status_comment = $data['comment'] ?? null;
        $index->locked = $data['status'] === 'approved';
        $index->save();

        return redirect()->route('indexes.show', $index)->with('status', 'Index status updated.');
    }
}
