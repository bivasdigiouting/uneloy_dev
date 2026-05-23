<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected WalletRepositoryInterface $walletRepository;

    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * Show the wallet management page
     */
    public function index(Request $request)
    {
        $registration = null;
        $availableAmount = null;
        if ($request->filled('search_id')) {
            $registration = $this->walletRepository->findByIdentifier($request->input('search_id'));
            if ($registration) {
                $availableAmount = $this->walletRepository->getBalance((int) $registration->id);
            }
        }

        return view('admin.wallet.management', compact('registration', 'availableAmount'));
    }

    /**
     * Search user by ID for wallet management
     */
    public function searchUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.wallet.management')
                ->withErrors($validator)
                ->withInput();
        }

        $registration = $this->walletRepository->findByIdentifier($request->search_id);

        if (! $registration) {
            return redirect()->route('admin.wallet.management')
                ->with('error', 'No user found with the provided ID/User ID/Aadhaar/Mobile/Name/Email.')
                ->withInput();
        }

        return redirect()->route('admin.wallet.management', ['search_id' => $registration->id]);
    }

    /**
     * Process wallet transaction (Add/Remove)
     */
    public function processTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:registrations,id',
            'transaction_type' => 'required|in:add,remove',
            'transaction_amount' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.wallet.management', ['search_id' => $request->input('user_id')])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $transaction = $this->walletRepository->processTransaction(
                (int) $request->user_id,
                $request->transaction_type,
                (float) $request->transaction_amount,
                $request->narration,
                Auth::id()
            );

            $message = $request->transaction_type === 'add'
                ? 'Amount ₹'.number_format((float) $transaction->amount, 2).' added to wallet successfully.'
                : 'Amount ₹'.number_format((float) $transaction->amount, 2).' removed from wallet successfully.';

            return redirect()->route('admin.wallet.management', ['search_id' => $request->user_id])
                ->with('success', $message.' New balance: ₹'.number_format((float) $transaction->new_balance, 2));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('admin.wallet.management', ['search_id' => $request->user_id])
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('admin.wallet.management', ['search_id' => $request->user_id])
                ->withInput()
                ->with('error', 'Failed to process transaction. Please try again.');
        }
    }
}
