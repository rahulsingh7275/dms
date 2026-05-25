<?php

namespace App\Http\Controllers;

use App\Models\Index;
use App\Models\IndexVerification;
use App\Models\Metadata;
use App\Models\MetadataVerification;
use App\Models\QcVerification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function indexVerifications()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $indexes = Index::with(['state', 'district', 'office'])->orderBy('status')->get();
        return view('verifications.index_verifications', compact('indexes'));
    }

    public function verifyIndex(Request $request, Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,sent_back'],
            'remarks' => ['nullable', 'string'],
        ]);

        IndexVerification::create([
            'index_id' => $index->id,
            'checker_id' => auth()->id(),
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
            'verified_at' => now(),
        ]);

        $index->update([
            'status' => $validated['status'],
            'locked' => $validated['status'] === 'approved',
        ]);

        return redirect()->route('verifications.index')->with('status', 'Index verification updated.');
    }

    public function metadataVerifications()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $metadataList = Metadata::with('deed.index')->orderBy('status')->get();
        return view('verifications.metadata', compact('metadataList'));
    }

    public function verifyMetadata(Request $request, Metadata $metadata)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'remarks' => ['nullable', 'string'],
        ]);

        MetadataVerification::create([
            'metadata_id' => $metadata->id,
            'checker_id' => auth()->id(),
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
            'verified_at' => now(),
        ]);

        $metadata->update(['status' => $validated['status']]);

        return redirect()->route('verifications.metadata')->with('status', 'Metadata verification updated.');
    }

    public function qcVerifications()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $indexes = Index::with(['state', 'district', 'office'])->orderBy('status')->get();
        return view('verifications.qc', compact('indexes'));
    }

    public function verifyQc(Request $request, Index $index)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,return_for_correction'],
            'remarks' => ['nullable', 'string'],
        ]);

        QcVerification::create([
            'index_id' => $index->id,
            'department_head_id' => auth()->id(),
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
            'verified_at' => now(),
        ]);

        $index->update(['status' => $validated['status']]);

        return redirect()->route('verifications.qc')->with('status', 'QC verification updated.');
    }
}
