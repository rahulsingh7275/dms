<?php

namespace App\Http\Controllers;

use App\Models\Deed;
use App\Models\District;
use App\Models\Index;
use App\Models\ScannedDocument;
use App\Models\State;
use App\Models\VaultRegistrationOffice;
use App\Models\DeedVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeedController extends Controller
{
    public function index(Request $request, Index $index)
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
        $presentationYear = trim((string) $request->input('presentation_year'));
        $deedNumber = trim((string) $request->input('deed_number'));
        $partyName = trim((string) $request->input('party_name'));
        $village = trim((string) $request->input('village'));
        $registrationDate = trim((string) $request->input('registration_date'));

        $query = $index->deeds()->with('scannedDocuments')->orderBy('deed_number');

        if (auth()->user()?->isChecker()) {
            $query->whereHas('scannedDocuments');
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($presentationYear !== '') {
            $query->where('presentation_year', 'like', '%' . $presentationYear . '%');
        }

        if ($deedNumber !== '') {
            $query->where('deed_number', 'like', '%' . $deedNumber . '%');
        }

        if ($partyName !== '') {
            $query->where('party_name', 'like', '%' . $partyName . '%');
        }

        if ($village !== '') {
            $query->where('village', 'like', '%' . $village . '%');
        }

        if ($registrationDate !== '') {
            $query->whereDate('registration_date', $registrationDate);
        }

        $deeds = $query->get();
        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $offices = VaultRegistrationOffice::orderBy('office_name')->get();

        return view('deeds.index', compact(
            'index',
            'deeds',
            'status',
            'stateId',
            'districtId',
            'officeId',
            'volumeYear',
            'bookNumber',
            'volumeNumber',
            'presentationYear',
            'deedNumber',
            'partyName',
            'village',
            'registrationDate',
            'states',
            'districts',
            'offices'
        ));
    }

    public function all(Request $request)
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
        $presentationYear = trim((string) $request->input('presentation_year'));
        $deedNumber = trim((string) $request->input('deed_number'));
        $partyName = trim((string) $request->input('party_name'));
        $village = trim((string) $request->input('village'));
        $registrationDate = trim((string) $request->input('registration_date'));

        $query = Deed::with(['index.state', 'index.district', 'index.office', 'scannedDocuments'])
            ->orderBy('deed_number');

        if (auth()->user()?->isChecker()) {
            $query->whereHas('scannedDocuments');
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($stateId !== null && $stateId !== '') {
            $query->whereHas('index', function ($query) use ($stateId) {
                $query->where('state_id', $stateId);
            });
        }

        if ($districtId !== null && $districtId !== '') {
            $query->whereHas('index', function ($query) use ($districtId) {
                $query->where('district_id', $districtId);
            });
        }

        if ($officeId !== null && $officeId !== '') {
            $query->whereHas('index', function ($query) use ($officeId) {
                $query->where('vault_registration_office_id', $officeId);
            });
        }

        if ($volumeYear !== '') {
            $query->whereHas('index', function ($query) use ($volumeYear) {
                $query->where('volume_year', 'like', '%' . $volumeYear . '%');
            });
        }

        if ($bookNumber !== '') {
            $query->whereHas('index', function ($query) use ($bookNumber) {
                $query->where('book_number', 'like', '%' . $bookNumber . '%');
            });
        }

        if ($volumeNumber !== '') {
            $query->whereHas('index', function ($query) use ($volumeNumber) {
                $query->where('volume_number', 'like', '%' . $volumeNumber . '%');
            });
        }

        if ($presentationYear !== '') {
            $query->where('presentation_year', 'like', '%' . $presentationYear . '%');
        }

        if ($deedNumber !== '') {
            $query->where('deed_number', 'like', '%' . $deedNumber . '%');
        }

        if ($partyName !== '') {
            $query->where('party_name', 'like', '%' . $partyName . '%');
        }

        if ($village !== '') {
            $query->where('village', 'like', '%' . $village . '%');
        }

        if ($registrationDate !== '') {
            $query->whereDate('registration_date', $registrationDate);
        }

        $deeds = $query->get();
        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $offices = VaultRegistrationOffice::orderBy('office_name')->get();

        return view('deeds.index', compact(
            'deeds',
            'status',
            'stateId',
            'districtId',
            'officeId',
            'volumeYear',
            'bookNumber',
            'volumeNumber',
            'presentationYear',
            'deedNumber',
            'partyName',
            'village',
            'registrationDate',
            'states',
            'districts',
            'offices'
        ));
    }

    public function show(Index $index, Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $deed->load(['scannedDocuments', 'metadata', 'deedVerifications', 'index.state', 'index.district', 'index.office']);

        if (auth()->user()?->isChecker() && $deed->scannedDocuments->isEmpty()) {
            return redirect()->route('indexes.deeds.index', $index)->with('error', 'Scanned copy is required before checker access.');
        }

        return view('deeds.show', compact('index', 'deed'));
    }

    public function showGlobal(Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $deed->load(['scannedDocuments', 'metadata', 'deedVerifications', 'index.state', 'index.district', 'index.office']);
        $index = $deed->index;

        if (auth()->user()?->isChecker() && $deed->scannedDocuments->isEmpty()) {
            return redirect()->route('deeds.index')->with('error', 'Scanned copy is required before checker access.');
        }

        return view('deeds.show', compact('index', 'deed'));
    }

    public function create(Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        return view('deeds.create', compact('index'));
    }

    public function store(Request $request, Index $index)
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
            'scanned_copy' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
        ]);

        // Use transaction so file storage failure won't leave a partial deed
        DB::beginTransaction();
        try {
            $deedData = $data;
            unset($deedData['scanned_copy']);

            $deed = $index->deeds()->create(array_merge($deedData, ['status' => 'pending']));

            // Handle scanned PDF upload
            if ($request->hasFile('scanned_copy')) {
                $file = $request->file('scanned_copy');
                if (! $file->isValid()) {
                    throw new \RuntimeException('Uploaded file is not valid.');
                }
                $path = $file->store('scanned_documents', 'public');

                ScannedDocument::create([
                    'deed_id' => $deed->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('indexes.deeds.index', $index)->with('status', 'Deed added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to store deed or scanned file: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Failed to save deed: ' . $e->getMessage());
        }
    }

    public function storeScannedCopy(Request $request, Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $user = auth()->user();
        if (! $user || ! $user->isOperator()) {
            return redirect()->back()->with('error', 'You do not have permission to upload scanned copies.');
        }

        if ($deed->status === 'approved') {
            return redirect()->back()->with('error', 'Cannot update scanned copy for an approved deed.');
        }

        $data = $request->validate([
            'scanned_copy' => ['required', 'file', 'mimes:pdf', 'max:15360'],
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('scanned_copy')) {
                $file = $request->file('scanned_copy');
                if (! $file->isValid()) {
                    throw new \RuntimeException('Uploaded file is not valid.');
                }

                // delete existing scanned documents for replacement
                $oldDocs = $deed->scannedDocuments;
                foreach ($oldDocs as $old) {
                    if (Storage::disk('public')->exists($old->file_path)) {
                        Storage::disk('public')->delete($old->file_path);
                    }
                    $old->delete();
                }

                $path = $file->store('scanned_documents', 'public');
                ScannedDocument::create([
                    'deed_id' => $deed->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('status', 'Scanned copy saved successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to upload scanned copy: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Failed to upload scanned copy: ' . $e->getMessage());
        }
    }

    public function edit(Index $index, Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $user = auth()->user();
        if ($user && $user->isChecker()) {
            return redirect()->route('indexes.deeds.show', [$index, $deed]);
        }

        // Prevent editing approved deeds
        if ($deed->status === 'approved') {
            return redirect()->route('indexes.deeds.show', [$index, $deed])->with('error', 'Cannot edit an approved deed.');
        }

        return view('deeds.edit', compact('index', 'deed'));
    }

    public function update(Request $request, Index $index, Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $user = auth()->user();
        if (! $user || (! $user->isOperator() && ! $user->isAdmin())) {
            return redirect()->route('indexes.deeds.show', [$index, $deed])->with('error', 'You do not have permission to update this deed.');
        }

        // Prevent updating approved deeds
        if ($deed->status === 'approved') {
            return redirect()->route('indexes.deeds.show', [$index, $deed])->with('error', 'Cannot update an approved deed.');
        }

        $data = $request->validate([
            'presentation_year' => ['required', 'string', 'max:20'],
            'deed_number' => ['required', 'string', 'max:100'],
            'party_name' => ['nullable', 'string', 'max:255'],
            'property_details' => ['nullable', 'string'],
            'village' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:100'],
            'registration_date' => ['nullable', 'date'],
            'scanned_copy' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
        ]);

        // Use transaction so file operations are atomic with the deed update
        DB::beginTransaction();
        try {
            $deed->update($data);

            // Handle scanned PDF upload (replacement)
            if ($request->hasFile('scanned_copy')) {
                // delete existing scanned document files and records
                $oldDocs = $deed->scannedDocuments;
                foreach ($oldDocs as $old) {
                    if (Storage::disk('public')->exists($old->file_path)) {
                        Storage::disk('public')->delete($old->file_path);
                    }
                    $old->delete();
                }

                $file = $request->file('scanned_copy');
                if (! $file->isValid()) {
                    throw new \RuntimeException('Uploaded file is not valid.');
                }
                $path = $file->store('scanned_documents', 'public');

                ScannedDocument::create([
                    'deed_id' => $deed->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('indexes.deeds.index', $index)->with('status', 'Deed updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update deed or scanned file: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Failed to update deed: ' . $e->getMessage());
        }
    }

    public function destroy(Index $index, Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $user = auth()->user();
        if (! $user || (! $user->isOperator() && ! $user->isAdmin())) {
            return redirect()->route('indexes.deeds.show', [$index, $deed])->with('error', 'You do not have permission to delete this deed.');
        }

        // Prevent deleting approved deeds
        if ($deed->status === 'approved') {
            return redirect()->route('indexes.deeds.index', $index)->with('error', 'Cannot delete an approved deed.');
        }

        $deed->delete();
        return redirect()->route('indexes.deeds.index', $index)->with('status', 'Deed removed successfully.');
    }

    public function download(Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $doc = $deed->scannedDocuments()->latest()->first();
        if (! $doc) {
            return redirect()->back()->with('error', 'No scanned document found for this deed.');
        }

        return Storage::disk('public')->download($doc->file_path, $doc->file_name);
    }

    public function updateStatus(Request $request, Index $index, Deed $deed)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $user = auth()->user();
        if (! $user || ! $user->isChecker()) {
            return redirect()->route('indexes.show', $index)->with('error', 'You do not have permission to change deed status.');
        }

        if (in_array($deed->status, ['approved', 'rejected'])) {
            return redirect()->route('indexes.show', $index)->with('error', 'Status cannot be changed after approval or rejection.');
        }

        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['status'] === 'rejected' && empty(trim($data['comment'] ?? ''))) {
            return redirect()->route('indexes.show', $index)->with('error', 'Please provide a comment when rejecting a deed.');
        }

        // Record verification in deed_verifications table
        DeedVerification::create([
            'deed_id' => $deed->id,
            'checker_id' => auth()->id(),
            'status' => $data['status'],
            'remarks' => $data['comment'] ?? null,
            'verified_at' => now(),
        ]);

        $deed->status = $data['status'];
        $deed->save();

        return redirect()->route('indexes.show', $index)->with('status', 'Deed status updated successfully.');
    }
}
