<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\MembershipUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipUploadController extends Controller
{
    public function index(Request $request): View
    {
        $query = MembershipUpload::query()->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('membership_name', 'like', "%{$search}%")
                    ->orWhere('membership_no', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        $uploads = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => MembershipUpload::query()->count(),
            'pending' => MembershipUpload::query()->where('status', 'pending')->count(),
            'verified' => MembershipUpload::query()->where('status', 'verified')->count(),
            'rejected' => MembershipUpload::query()->where('status', 'rejected')->count(),
        ];

        return view('sop.membership-uploads.index', [
            'uploads' => $uploads,
            'stats' => $stats,
            'search' => $search ?? '',
            'statusFilter' => $status ?? '',
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function edit(MembershipUpload $membershipUpload): View
    {
        return view('sop.membership-uploads.edit', [
            'upload' => $membershipUpload,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function update(Request $request, MembershipUpload $membershipUpload): RedirectResponse
    {
        $validated = $request->validate([
            'membership_name' => ['required', 'string', 'max:255'],
            'membership_no' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'registration_number' => ['nullable', 'string', 'max:40'],
            'status' => ['required', 'in:'.implode(',', MembershipUpload::statusOptions())],
            'notes' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('photo')) {
            $membershipUpload->deletePhoto();
            $validated['photo_path'] = MembershipUpload::storePhoto($request->file('photo'));
        }

        unset($validated['photo']);

        $membershipUpload->update($validated);

        return redirect()
            ->route('controlpanel.membership-uploads.edit', $membershipUpload)
            ->with('status', 'Membership record updated successfully.');
    }

    public function destroy(MembershipUpload $membershipUpload): RedirectResponse
    {
        $membershipUpload->deletePhoto();
        $membershipUpload->delete();

        return redirect()
            ->route('controlpanel.membership-uploads.index')
            ->with('status', 'Membership record deleted.');
    }

    /** @return list<string> */
    private function statusOptions(): array
    {
        return MembershipUpload::statusOptions();
    }
}
