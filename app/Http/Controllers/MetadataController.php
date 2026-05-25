<?php

namespace App\Http\Controllers;

use App\Models\Deed;
use App\Models\District;
use App\Models\Metadata;
use App\Models\State;
use App\Models\VaultRegistrationOffice;
use Illuminate\Http\Request;

class MetadataController extends Controller
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
        $volume = trim((string) $request->input('volume'));
        $book = trim((string) $request->input('book'));
        $deedNumber = trim((string) $request->input('deed_number'));
        $presentationYear = trim((string) $request->input('presentation_year'));
        $partyName = trim((string) $request->input('party_name'));
        $village = trim((string) $request->input('village'));
        $date = trim((string) $request->input('date'));

        $metadataQuery = Metadata::with([
            'deed.index.state',
            'deed.index.district',
            'deed.index.office',
        ])
            ->orderByDesc('created_at');

        if ($status !== '') {
            $metadataQuery->where('status', $status);
        }

        if ($stateId !== null && $stateId !== '') {
            $metadataQuery->whereHas('deed.index', function ($query) use ($stateId) {
                $query->where('state_id', $stateId);
            });
        }

        if ($districtId !== null && $districtId !== '') {
            $metadataQuery->whereHas('deed.index', function ($query) use ($districtId) {
                $query->where('district_id', $districtId);
            });
        }

        if ($officeId !== null && $officeId !== '') {
            $metadataQuery->whereHas('deed.index', function ($query) use ($officeId) {
                $query->where('vault_registration_office_id', $officeId);
            });
        }

        if ($volume !== '') {
            $metadataQuery->whereHas('deed.index', function ($query) use ($volume) {
                $query->where('volume_number', 'like', '%' . $volume . '%')
                    ->orWhere('volume_year', 'like', '%' . $volume . '%');
            });
        }

        if ($book !== '') {
            $metadataQuery->whereHas('deed.index', function ($query) use ($book) {
                $query->where('book_number', 'like', '%' . $book . '%');
            });
        }

        if ($deedNumber !== '') {
            $metadataQuery->where('deed_number', 'like', '%' . $deedNumber . '%');
        }

        if ($presentationYear !== '') {
            $metadataQuery->where('presentation_year', 'like', '%' . $presentationYear . '%');
        }

        if ($partyName !== '') {
            $metadataQuery->where('party_name', 'like', '%' . $partyName . '%');
        }

        if ($village !== '') {
            $metadataQuery->where('village', 'like', '%' . $village . '%');
        }

        if ($date !== '') {
            $metadataQuery->whereDate('registration_date', $date);
        }

        $metadataList = $metadataQuery->get();

        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $offices = VaultRegistrationOffice::orderBy('office_name')->get();

        return view('metadata.index', compact(
            'metadataList',
            'status',
            'stateId',
            'districtId',
            'officeId',
            'volume',
            'book',
            'deedNumber',
            'presentationYear',
            'partyName',
            'village',
            'date',
            'states',
            'districts',
            'offices'
        ));
    }

    public function create(Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        return view('metadata.create', compact('deed'));
    }

    public function store(Request $request, Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'presentation_year' => ['required', 'string', 'max:20'],
            'deed_number' => ['required', 'string', 'max:100'],
            'party_name' => ['nullable', 'string', 'max:255'],
            'property_details' => ['nullable', 'string'],
            'village' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:100'],
            'registration_date' => ['nullable', 'date'],
        ]);
        
        $deed->metadata()->create(array_merge($data, [
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]));

        return redirect()->route('indexes.deeds.index', $deed->index)->with('status', 'Metadata saved successfully.');
    }

    public function edit(Deed $deed, Metadata $metadata)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        return view('metadata.edit', compact('deed', 'metadata'));
    }

    public function update(Request $request, Deed $deed, Metadata $metadata)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $data = $request->validate([
            'presentation_year' => ['required', 'string', 'max:20'],
            'deed_number' => ['required', 'string', 'max:100'],
            'party_name' => ['nullable', 'string', 'max:255'],
            'property_details' => ['nullable', 'string'],
            'village' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:100'],
            'registration_date' => ['nullable', 'date'],
        ]);

        $metadata->update($data);
        return redirect()->route('indexes.deeds.index', $deed->index)->with('status', 'Metadata updated successfully.');
    }
}
