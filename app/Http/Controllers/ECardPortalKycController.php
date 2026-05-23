<?php

namespace App\Http\Controllers;

use App\Models\ECardUserKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ECardPortalKycController extends Controller
{
    protected array $fields = [
        'aadhaar_front',
        'aadhaar_back',
        'pan_front',
        'pan_back',
        'cheque_book',
        'business_document',
        'business_photo',
        'signature',
    ];

    public function index(Request $request)
    {
        $user = Auth::guard('ecard')->user();
        if (! $user) {
            return redirect()->route('ecard.login');
        }

        $kyc = ECardUserKyc::where('ecard_registration_id', $user->id)->first();

        return view('ecard.kyc.index', [
            'user' => $user,
            'kyc' => $kyc,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('ecard')->user();
        if (! $user) {
            return redirect()->route('ecard.login');
        }

        $rules = [];
        foreach ($this->fields as $f) {
            $rules[$f] = 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120';
        }
        $validated = $request->validate($rules);

        $kyc = ECardUserKyc::firstOrCreate(
            ['ecard_registration_id' => $user->id],
            []
        );

        foreach ($this->fields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($kyc->{$field}) {
                    Storage::disk('public')->delete($kyc->{$field});
                }
                $path = $request->file($field)->store(
                    'kyc/'.$user->id,
                    'public'
                );
                $kyc->{$field} = $path;
            }
        }

        $kyc->save();

        return redirect()->route('ecard.kyc.index')->with('success', 'KYC documents updated successfully.');
    }

    public function destroy(string $field)
    {
        $user = Auth::guard('ecard')->user();
        if (! $user) {
            return redirect()->route('ecard.login');
        }

        if (! in_array($field, $this->fields, true)) {
            return response()->json(['message' => 'Invalid field.'], 422);
        }

        $kyc = ECardUserKyc::where('ecard_registration_id', $user->id)->first();
        if (! $kyc || ! $kyc->{$field}) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        Storage::disk('public')->delete($kyc->{$field});
        $kyc->{$field} = null;
        $kyc->save();

        return response()->json(['message' => 'File deleted successfully.']);
    }
}
