<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPayment;
use App\Services\AdmissionFormsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdmissionFormsController extends Controller
{
    public function __construct(private AdmissionFormsService $forms) {}

    public function index(Request $request): View
    {
        $type = $request->query('type', 'online');
        if (! array_key_exists($type, $this->forms->types())) {
            $type = 'online';
        }

        return view('sop.admission-forms.index', [
            'types' => $this->forms->types(),
            'counts' => $this->forms->counts(),
            'currentType' => $type,
            'currentTypeLabel' => $this->forms->typeLabel($type),
            'records' => $this->forms->paginate(
                $type,
                $request->query('q'),
                $request->query('status'),
            ),
            'statusOptions' => $this->forms->statusOptions(),
            'search' => $request->query('q'),
            'statusFilter' => $request->query('status'),
        ]);
    }

    public function show(string $type, int $id): View
    {
        $record = $this->forms->find($type, $id);

        return view('sop.admission-forms.show', [
            'type' => $type,
            'typeLabel' => $this->forms->typeLabel($type),
            'record' => $record,
            'reference' => $this->forms->referenceNumber($record, $type),
            'formFields' => $this->forms->formDataFields($record, $type),
            'statusOptions' => $this->forms->statusOptions(),
            'photoUrl' => $this->forms->photoUrl($record->photo_path ?? null),
            'payments' => AdmissionPayment::query()
                ->where('payable_type', $record::class)
                ->where('payable_id', $record->getKey())
                ->latest()
                ->get(),
        ]);
    }

    public function updateStatus(Request $request, string $type, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in($this->forms->statusOptions())],
            'payment_status' => ['nullable', Rule::in(['pending', 'partial', 'paid', 'failed'])],
        ]);

        $record = $this->forms->find($type, $id);
        $payload = ['status' => $validated['status']];

        if ($type === 'online' && isset($validated['payment_status'])) {
            $payload['payment_status'] = $validated['payment_status'];
        }

        $record->update($payload);

        return back()->with('status', 'Application updated successfully.');
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $this->forms->delete($type, $id);

        return redirect()
            ->route('controlpanel.admission-forms.index', ['type' => $type])
            ->with('status', 'Application deleted.');
    }
}
