<?php

namespace App\Http\Controllers;

use App\Models\Index;
use App\Models\District;
use App\Models\VaultRegistrationOffice;
// Intentionally not importing Request to avoid naming conflicts; use fully-qualified type hints

class MetadataVolumeController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $districtId = $request->input('district_id');
        $officeId = $request->input('office_id');
        $volume = trim((string) $request->input('volume'));

        $query = Index::with(['state', 'district', 'office'])
            ->whereHas('deeds')
            ->whereDoesntHave('deeds', function ($q) {
                $q->whereDoesntHave('metadata')
                    ->orWhereHas('metadata', function ($mq) {
                        $mq->where('status', '!=', 'approved');
                    });
            });

        if ($districtId !== null && $districtId !== '') {
            $query->where('district_id', $districtId);
        }

        if ($officeId !== null && $officeId !== '') {
            $query->where('vault_registration_office_id', $officeId);
        }

        if ($volume !== '') {
            $query->where(function ($q) use ($volume) {
                $q->where('volume_number', 'like', '%' . $volume . '%')
                    ->orWhere('volume_year', 'like', '%' . $volume . '%');
            });
        }

        $indexes = $query->orderBy('volume_number')->get();

        $districts = District::orderBy('name')->get();
        $offices = VaultRegistrationOffice::orderBy('office_name')->get();

        return view('metadata.volume.MetadataVolumeList', compact('indexes', 'districts', 'offices', 'districtId', 'officeId', 'volume'));
    }

    public function process(Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        return redirect()->route('indexes.deeds.index', $index->id);
    }

    public function submit(\Illuminate\Http\Request $request, Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $index->update([
            'is_volume_forwarded' => true,
            'final_approve' => true,
        ]);

        return redirect()->route('metadata.volumes.index')->with('status', 'Volume submitted and final_approved.');
    }
}
