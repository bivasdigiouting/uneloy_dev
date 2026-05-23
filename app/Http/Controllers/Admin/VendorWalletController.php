<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\VendorWalletRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorWalletController extends Controller
{
    protected VendorWalletRepositoryInterface $walletRepository;

    public function __construct(VendorWalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * Show the vendor wallet management page
     */
    public function index(Request $request)
    {
        $vendor = null;
        if ($request->filled('search_id')) {
            $vendor = $this->walletRepository->findByIdentifier($request->input('search_id'));
        }

        return view('admin.vendor-wallet.management', compact('vendor'));
    }

    /**
     * Search vendor by ID for wallet management
     */
    public function searchVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vendor = $this->walletRepository->findByIdentifier($request->search_id);

        if (! $vendor) {
            return redirect()->back()
                ->with('error', 'No vendor found with the provided ID/Vendor No/Mobile/Name/Email.')
                ->withInput();
        }

        return view('admin.vendor-wallet.management', compact('vendor'));
    }

    /**
     * Process wallet transaction (Add/Remove)
     */
    public function processTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'transaction_type' => 'required|in:add,remove',
            'transaction_amount' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $transaction = $this->walletRepository->processTransaction(
                (int) $request->vendor_id,
                $request->transaction_type,
                (float) $request->transaction_amount,
                $request->narration,
                Auth::id()
            );

            $message = $request->transaction_type === 'add'
                ? 'Amount ₹'.number_format((float) $transaction->amount, 2).' added to vendor wallet successfully.'
                : 'Amount ₹'.number_format((float) $transaction->amount, 2).' removed from vendor wallet successfully.';

            return redirect()->route('admin.vendor.wallet.management')
                ->with('success', $message.' New balance: ₹'.number_format((float) $transaction->new_balance, 2));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to process transaction. Please try again.');
        }
    }
}
