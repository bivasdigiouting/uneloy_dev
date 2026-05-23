<?php

namespace App\Http\Controllers\ECard;

use App\Models\ECardRegistration;
use App\Models\ECardWalletTransaction;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Models\EcardSale;
use App\Models\EcardSaleItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sales = EcardSale::where('ecard_registration_id', Auth::guard('ecard')->id())
                ->where('payment_status', 'paid')
                ->orderBy('created_at', 'desc');

            return DataTables::of($sales)
                ->addIndexColumn()
                ->editColumn('billing_date', function ($sale) {
                    return $sale->billing_date ? $sale->billing_date->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($sale) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="' . route('ecard.sales.show', $sale->id) . '" class="btn btn-info btn-sm" title="View Details"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="' . route('ecard.sales.edit', $sale->id) . '" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a href="' . route('ecard.sales.invoice', $sale->id) . '" class="btn btn-secondary btn-sm" title="Invoice" target="_blank"><i class="fas fa-file-invoice"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ecard.sales.index');
    }

    public function create()
    {
        $products = Product::with('gstTax')->where('is_active', true)->get();
        $users = User::select('id', 'name', 'phone', 'email', 'user_id')->orderBy('name')->get();
        return view('ecard.sales.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'billing_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'customer_type' => 'required|in:existing,walk_in',
        ];

        if ($request->customer_type === 'existing') {
            $rules['user_id'] = 'required|exists:users,id';
        } else {
            $rules['customer_name'] = 'required|string|max:255';
            if ($request->has('create_account') && $request->create_account == 1) {
                $rules['email'] = 'required|email|unique:users,email';
                $rules['phone'] = 'required|string|max:20|unique:users,phone';
                $rules['password'] = 'required|string|min:6';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $userId = null;
            $customerName = '';

            if ($request->customer_type === 'existing') {
                $user = User::findOrFail($request->user_id);
                $userId = $user->id;
                $customerName = $user->name;
            } else {
                $customerName = $request->customer_name;
                
                if ($request->has('create_account') && $request->create_account == 1) {
                    $user = new User();
                    $user->name = $request->customer_name;
                    $user->email = $request->email;
                    $user->phone = $request->phone;
                    $user->password = Hash::make($request->password);
                    $user->user_id = 'USER' . strtoupper(Str::random(8));
                    $user->save();
                    
                    $userId = $user->id;
                }
            }

            $totalPurchaseValue = 0;
            $totalTaxAmount = 0;
            $grandTotal = 0;

            $sale = new EcardSale();
            $sale->ecard_registration_id = Auth::guard('ecard')->id();
            $sale->user_id = $userId;
            $sale->customer_name = $customerName;
            $sale->billing_date = $request->billing_date;
            // Temporary values, will update after calculating items
            $sale->purchase_value = 0;
            $sale->tax_amount = 0;
            $sale->total_amount = 0;
            $sale->save();

            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']);
                
                // Assuming product price includes tax or tax is calculated on top. 
                // Based on previous context, GST tax is separate.
                // Let's assume price is base price.
                // If GST is associated with product, we should calculate it.
                // For now, let's keep it simple or check if product has tax.
                // Product model has 'gst_tax_id'.
                
                $price = $product->price ?? 0;
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity;
                
                $taxAmount = 0;
                if ($product->gstTax) {
                     $taxPercentage = $product->gstTax->rate_percent ?? 0;
                     $taxAmount = ($subtotal * $taxPercentage) / 100;
                }

                $itemTotal = $subtotal + $taxAmount;

                EcardSaleItem::create([
                    'ecard_sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $itemTotal,
                ]);

                $totalPurchaseValue += $subtotal;
                $totalTaxAmount += $taxAmount;
                $grandTotal += $itemTotal;
            }

            $sale->update([
                'purchase_value' => $totalPurchaseValue,
                'tax_amount' => $totalTaxAmount,
                'total_amount' => $grandTotal,
            ]);

            DB::commit();

            return redirect()->route('ecard.sales.payment', $sale->id)->with('success', 'Sale created. Please complete payment.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating sale: ' . $e->getMessage())->withInput();
        }
    }

    public function paymentSelection($id)
    {
        $sale = EcardSale::with(['items.product'])->where('ecard_registration_id', Auth::guard('ecard')->id())->findOrFail($id);
        
        if ($sale->payment_status === 'paid') {
            return redirect()->route('ecard.sales.show', $id)->with('info', 'Sale already paid.');
        }

        $user = Auth::guard('ecard')->user();
        $gateways = PaymentGateway::where('is_enabled', true)->get();

        $qrPayUrl = null;
        $qrSvg = null;
        $qrToken = data_get($sale->payment_details, 'qr_token');
        if ($sale->payment_method === 'uonley_qr' && $sale->payment_status === 'pending' && is_string($qrToken) && $qrToken !== '') {
            $qrPayUrl = route('ecard.sales.qr-pay', ['id' => $sale->id, 'token' => $qrToken]);
            $qrSvg = QrCode::format('svg')->size(240)->margin(1)->errorCorrection('H')->generate($qrPayUrl);
        }

        return view('ecard.sales.payment', compact('sale', 'user', 'gateways', 'qrPayUrl', 'qrSvg'));
    }

    public function processPayment(Request $request, $id)
    {
        $sale = EcardSale::where('ecard_registration_id', Auth::guard('ecard')->id())->findOrFail($id);
        
        if ($sale->payment_status === 'paid') {
            return redirect()->route('ecard.sales.show', $id)->with('info', 'Sale already paid.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:uonley_card,uonley_qr,main_wallet,bonus_wallet,gateway',
            'gateway_id' => 'required_if:payment_method,gateway|exists:payment_gateways,id',
        ]);

        $user = Auth::guard('ecard')->user();

        if ($validated['payment_method'] === 'main_wallet') {
            if ($user->wallet_balance < $sale->total_amount) {
                return back()->with('error', 'Insufficient balance in Main Wallet.');
            }

            try {
                DB::beginTransaction();
                
                $previous_balance = $user->wallet_balance;
                $user->wallet_balance -= $sale->total_amount;
                $user->save();

                ECardWalletTransaction::create([
                    'ecard_registration_id' => $user->id,
                    'transaction_type' => 'remove',
                    'amount' => $sale->total_amount,
                    'previous_balance' => $previous_balance,
                    'new_balance' => $user->wallet_balance,
                    'narration' => 'Payment for Sale #' . $sale->id,
                    'reference_type' => 'ecard_sale',
                    'reference_id' => $sale->id,
                    'payment_status' => 'success',
                ]);

                $sale->update([
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'main_wallet',
                    'transaction_id' => 'WLT_' . Str::random(10),
                ]);

                DB::commit();
                return redirect()->route('ecard.sales.index')->with('success', 'Payment successful and sale generated.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Payment failed: ' . $e->getMessage());
            }
        }

        if ($validated['payment_method'] === 'bonus_wallet') {
            if ($user->bonus_wallet_balance < $sale->total_amount) {
                return back()->with('error', 'Insufficient balance in Bonus Wallet.');
            }

            try {
                DB::beginTransaction();
                
                $previous_balance = $user->bonus_wallet_balance;
                $user->bonus_wallet_balance -= $sale->total_amount;
                $user->save();

                // Assuming we use the same transaction table or similar logic for bonus
                ECardWalletTransaction::create([
                    'ecard_registration_id' => $user->id,
                    'transaction_type' => 'remove',
                    'amount' => $sale->total_amount,
                    'previous_balance' => $previous_balance,
                    'new_balance' => $user->bonus_wallet_balance,
                    'narration' => 'Bonus Payment for Sale #' . $sale->id,
                    'reference_type' => 'ecard_sale',
                    'reference_id' => $sale->id,
                    'payment_status' => 'success',
                ]);

                $sale->update([
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'bonus_wallet',
                    'transaction_id' => 'BWLT_' . Str::random(10),
                ]);

                DB::commit();
                return redirect()->route('ecard.sales.index')->with('success', 'Payment successful and sale generated.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Payment failed: ' . $e->getMessage());
            }
        }

        if ($validated['payment_method'] === 'uonley_card') {
            $cardValidated = $request->validate([
                'card_number' => ['required', 'digits:16'],
                'expiry_month' => ['required', 'integer', 'between:1,12'],
                'expiry_year' => ['required', 'integer', 'min:0'],
                'cvv' => ['required', 'digits_between:3,4'],
            ]);

            $expiryYear = (int) $cardValidated['expiry_year'];
            if ($expiryYear < 100) {
                $expiryYear += 2000;
            }

            $details = (array) ($sale->payment_details ?? []);
            $details['uonley_card'] = [
                'last4' => substr($cardValidated['card_number'], -4),
                'expiry_month' => (int) $cardValidated['expiry_month'],
                'expiry_year' => $expiryYear,
            ];

            $sale->update([
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'uonley_card',
                'transaction_id' => 'UCARD_' . Str::upper(Str::random(10)),
                'payment_details' => $details,
            ]);

            return redirect()->route('ecard.sales.index')->with('success', 'Payment successful via Uonley Card.');
        }

        if ($validated['payment_method'] === 'uonley_qr') {
            $details = (array) ($sale->payment_details ?? []);
            if (empty($details['qr_token']) || ! is_string($details['qr_token'])) {
                $details['qr_token'] = Str::random(48);
                $details['qr_generated_at'] = now()->toDateTimeString();
            }

            $sale->update([
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'uonley_qr',
                'payment_details' => $details,
            ]);

            return redirect()->route('ecard.sales.payment', $sale->id)->with('success', 'QR generated. Ask customer to scan and pay.');
        }

        if ($validated['payment_method'] === 'gateway') {
            $gateway = PaymentGateway::find($validated['gateway_id']);
            // Here you would integrate the actual gateway (PhonePe, Cashfree, etc.)
            // For now, let's redirect to a callback as a simulation or implement a basic flow.
            // If Cashfree/PhonePe logic exists, reuse it.
            
            // Simulation for now or use existing ECardPortalRegistrationController logic if applicable
            return $this->initiateGatewayPayment($sale, $gateway);
        }

        return back()->with('error', 'Invalid payment method.');
    }

    private function initiateGatewayPayment($sale, $gateway)
    {
        // This should contain actual gateway integration logic
        // For simulation, let's just mark it as paid if it's a "test" gateway or redirect to a fake success page
        
        if (config('app.env') === 'local') {
             return redirect()->route('ecard.sales.payment.callback', ['id' => $sale->id, 'status' => 'success', 'gateway' => $gateway->slug]);
        }

        return back()->with('error', 'Gateway integration in progress for ' . $gateway->name);
    }

    public function handlePaymentCallback(Request $request, $id)
    {
        $sale = EcardSale::findOrFail($id);
        
        if ($request->status === 'success') {
            $sale->update([
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'gateway_' . ($request->gateway ?? 'unknown'),
                'transaction_id' => $request->transaction_id ?? ('GTW_' . Str::random(10)),
                'payment_details' => $request->all(),
            ]);
            return redirect()->route('ecard.sales.index')->with('success', 'Payment successful.');
        }

        return redirect()->route('ecard.sales.payment', $id)->with('error', 'Payment failed or cancelled.');
    }

    public function qrPay(Request $request, $id)
    {
        $sale = EcardSale::with('ecardRegistration')->findOrFail($id);

        if ($sale->payment_status === 'paid') {
            return view('ecard.sales.qr_pay', [
                'sale' => $sale,
                'seller' => $sale->ecardRegistration,
                'payer' => Auth::guard('ecard')->user(),
                'token' => (string) $request->query('token', ''),
                'isValid' => false,
            ])->with('info', 'Sale already paid.');
        }

        $token = (string) $request->query('token', '');
        $expectedToken = (string) data_get($sale->payment_details, 'qr_token', '');
        $isValid = $sale->payment_method === 'uonley_qr'
            && $sale->payment_status === 'pending'
            && $token !== ''
            && $expectedToken !== ''
            && hash_equals($expectedToken, $token);

        return view('ecard.sales.qr_pay', [
            'sale' => $sale,
            'seller' => $sale->ecardRegistration,
            'payer' => Auth::guard('ecard')->user(),
            'token' => $token,
            'isValid' => $isValid,
        ]);
    }

    public function qrPayProcess(Request $request, $id)
    {
        $payerId = Auth::guard('ecard')->id();
        if (! $payerId) {
            return redirect()->route('ecard.login')->with('error', 'Please login to continue.');
        }

        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        try {
            DB::transaction(function () use ($id, $validated, $payerId) {
                $sale = EcardSale::lockForUpdate()->findOrFail($id);

                $expectedToken = (string) data_get($sale->payment_details, 'qr_token', '');
                if ($sale->payment_status === 'paid') {
                    throw new \RuntimeException('Sale already paid.');
                }

                if (
                    $sale->payment_method !== 'uonley_qr'
                    || $sale->payment_status !== 'pending'
                    || $expectedToken === ''
                    || ! hash_equals($expectedToken, (string) $validated['token'])
                ) {
                    throw new \RuntimeException('Invalid or expired QR.');
                }

                $payer = ECardRegistration::lockForUpdate()->findOrFail($payerId);
                $seller = ECardRegistration::lockForUpdate()->findOrFail($sale->ecard_registration_id);

                if ($payer->wallet_balance < $sale->total_amount) {
                    throw new \RuntimeException('Insufficient balance in wallet.');
                }

                $payerPrevious = $payer->wallet_balance;
                $payer->wallet_balance = $payerPrevious - $sale->total_amount;
                $payer->save();

                $sellerPrevious = $seller->wallet_balance;
                $seller->wallet_balance = $sellerPrevious + $sale->total_amount;
                $seller->save();

                ECardWalletTransaction::create([
                    'ecard_registration_id' => $payer->id,
                    'transaction_type' => 'remove',
                    'amount' => $sale->total_amount,
                    'previous_balance' => $payerPrevious,
                    'new_balance' => $payer->wallet_balance,
                    'narration' => 'QR Payment for Sale #' . $sale->id,
                    'performed_by_id' => $payer->id,
                    'reference_type' => 'ecard_sale_qr_payment',
                    'reference_id' => $sale->id,
                    'payment_status' => 'success',
                ]);

                ECardWalletTransaction::create([
                    'ecard_registration_id' => $seller->id,
                    'transaction_type' => 'add',
                    'amount' => $sale->total_amount,
                    'previous_balance' => $sellerPrevious,
                    'new_balance' => $seller->wallet_balance,
                    'narration' => 'QR Received for Sale #' . $sale->id,
                    'performed_by_id' => $payer->id,
                    'reference_type' => 'ecard_sale_qr_received',
                    'reference_id' => $sale->id,
                    'payment_status' => 'success',
                ]);

                $details = (array) ($sale->payment_details ?? []);
                $details['qr_paid_by_ecard_registration_id'] = $payer->id;
                $details['qr_paid_at'] = now()->toDateTimeString();

                $sale->update([
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'uonley_qr',
                    'transaction_id' => 'UQR_' . Str::upper(Str::random(10)),
                    'payment_details' => $details,
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()->route('ecard.sales.qr-pay', ['id' => $id, 'token' => $validated['token']])->with('error', $e->getMessage());
        }

        return redirect()->route('ecard.sales.qr-pay', ['id' => $id, 'token' => $validated['token']])->with('success', 'Payment successful. Sale completed.');
    }

    public function show($id)
    {
        $sale = EcardSale::with(['items.product', 'items.product.gstTax'])->where('ecard_registration_id', Auth::guard('ecard')->id())->findOrFail($id);
        return view('ecard.sales.show', compact('sale'));
    }

    public function edit($id)
    {
        $sale = EcardSale::with('items')->where('ecard_registration_id', Auth::guard('ecard')->id())->findOrFail($id);
        $products = Product::where('is_active', true)->get();
        return view('ecard.sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, $id)
    {
        $sale = EcardSale::where('ecard_registration_id', Auth::guard('ecard')->id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'billing_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Update basic info
            $sale->customer_name = $request->customer_name;
            $sale->billing_date = $request->billing_date;
            $sale->save();

            // Remove old items
            $sale->items()->delete();

            $totalPurchaseValue = 0;
            $totalTaxAmount = 0;
            $grandTotal = 0;

            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']);
                
                $price = $product->price ?? 0;
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity;
                
                $taxAmount = 0;
                if ($product->gstTax) {
                     $taxPercentage = $product->gstTax->rate_percent ?? 0;
                     $taxAmount = ($subtotal * $taxPercentage) / 100;
                }

                $itemTotal = $subtotal + $taxAmount;

                EcardSaleItem::create([
                    'ecard_sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $itemTotal,
                ]);

                $totalPurchaseValue += $subtotal;
                $totalTaxAmount += $taxAmount;
                $grandTotal += $itemTotal;
            }

            $sale->update([
                'purchase_value' => $totalPurchaseValue,
                'tax_amount' => $totalTaxAmount,
                'total_amount' => $grandTotal,
            ]);

            DB::commit();

            return redirect()->route('ecard.sales.index')->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating sale: ' . $e->getMessage())->withInput();
        }
    }

    public function invoice($id)
    {
        $sale = EcardSale::with(['items.product', 'items.product.gstTax', 'ecardRegistration'])->where('ecard_registration_id', Auth::guard('ecard')->id())->findOrFail($id);
        return view('ecard.sales.invoice', compact('sale'));
    }
}
