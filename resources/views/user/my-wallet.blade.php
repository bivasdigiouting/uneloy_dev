<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My Wallet - UOnly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('user.partials.theme-style')
    <style>
        /* :root {
            --primary-gradient: linear-gradient(135deg, #D53F8C 0%, #805AD5 100%);
            --bg-light: #f3f4f6;
            --text-dark: #333333;
            --text-muted: #718096;
            --primary-color: #d53f8c;
        } */
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: var(--bg-light);
        }

        .mobile-wrapper {
            width: 100%;
            background-color: var(--bg-light);
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Header Section with Gradient Background */
        .wallet-header {
            background: var(--primary-gradient);
            padding: 20px;
            color: white;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .header-top {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-btn {
            color: white;
            font-size: 20px;
            margin-right: 15px;
            text-decoration: none;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
        }

        .sub-title {
            font-size: 14px;
            opacity: 0.9;
            margin-left: 35px; /* Align with title text */
            margin-top: -5px;
        }

        /* Custom Tabs */
        .custom-tabs {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 5px;
            display: flex;
            margin-top: 20px;
        }

        .tab-item {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .tab-item.active {
            background: white;
            color: var(--pink-highlight);
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            background: white;
            padding: 20px;
        }

        /* Balance Display */
        .balance-container {
            text-align: center;
            padding: 30px 0;
        }

        .balance-label {
            color: #fff;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .balance-amount {
            font-size: 48px;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        
        #noteInputWrapper input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Transactions List */
        .transactions-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .no-transactions {
            text-align: center;
            padding: 40px 0;
            color: var(--text-muted);
        }

        .transaction-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .trans-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pink-highlight);
            margin-right: 15px;
        }

        .trans-details {
            flex: 1;
        }
        .trans-title {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 15px;
        }

        .trans-date {
            font-size: 12px;
            color: var(--text-muted);
        }

        .trans-amount {
            font-weight: 600;
            font-size: 15px;
        }

        .amount-credit {
            color: #28a745;
        }

        .amount-debit {
            color: #dc3545;
        }

        /* Keypad Styles */
        .keypad-container {
            padding: 20px;
            margin-top: auto;
        }
        
        .add-note-link {
            text-align: center;
            display: block;
            color: white;
            text-decoration: underline;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .keypad-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .key-btn {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: none;
            background: white;
            color: var(--text-dark);
            font-size: 24px;
            font-weight: 500;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: background 0.2s;
        }

        .key-btn:active {
            background: #f0f0f0;
        }

        .pay-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            margin-top: 10px;
        }

        /* Tab Content visibility */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }

        /* Desktop specific adjustments */
        @media (min-width: 768px) {
            body {
                background-color: #e2e8f0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .mobile-wrapper {
                border-radius: 20px;
                overflow: hidden;
                height: 90vh;
                max-height: 800px;
                overflow-y: auto;
                width: 100%;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>

<div class="desktop-wrapper d-none d-lg-flex bg-light min-vh-100" style="width: 100%; margin-left: 294px;">
    @include('user.partials.desktop-sidebar')
    <div class="flex-grow-1 d-flex flex-column ms-auto" style="margin-left: 280px;">
        @section('page_title', 'My Wallet')
        @include('user.partials.desktop-header')
        
        <main class="p-4">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="row g-4">
                    <!-- Wallet Balance & Add Money -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold text-secondary mb-4">Wallet Balance</h5>
                                <div class="text-center py-4 bg-primary bg-opacity-10 rounded-4 mb-4">
                                    <h2 class="display-4 fw-bold text-primary mb-0">₹{{ number_format($user->wallet_balance ?? 0, 2) }}</h2>
                                    <small class="text-muted">Available Funds</small>
                                </div>
                                
                                <hr class="my-4">
                                
                                <h6 class="fw-bold mb-3">Add Money to Wallet</h6>
                                <form action="{{ route('user.wallet.initiate') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Amount</label>
                                        @if(!empty($isFirstWalletTopup))
                                            <select name="amount" class="form-select form-select-lg border-0 bg-light fw-bold" required>
                                                <option value="150">₹150 (Get ₹50 bonus)</option>
                                                <option value="500">₹500 (Get ₹300 bonus)</option>
                                            </select>
                                            <div class="small text-muted mt-2">First wallet recharge supports only ₹150 or ₹500.</div>
                                        @else
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text border-0 bg-light">₹</span>
                                                <input type="number" name="amount" class="form-control border-0 bg-light fw-bold" placeholder="0.00" min="1" step="any" required>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small">Note (Optional)</label>
                                        <input type="text" name="note" class="form-control bg-light border-0" placeholder="e.g. For Recharge">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                                        <i class="fas fa-plus-circle me-2"></i> Add Money
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions History -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white border-0 py-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="fw-bold mb-0">Recent Transactions</h5>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <form action="{{ route('user.wallet.show') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                            <a href="{{ route('user.wallet.show') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                        </form>
                                        <a href="{{ route('user.wallet.show', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-file-excel me-1"></i> Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-secondary">
                                        <tr>
                                            <th class="border-0 px-4 py-3 small text-uppercase fw-semibold">Date & Time</th>
                                            <th class="border-0 py-3 small text-uppercase fw-semibold">Description</th>
                                            <th class="border-0 py-3 small text-uppercase fw-semibold text-center">Type</th>
                                            <th class="border-0 px-4 py-3 small text-uppercase fw-semibold text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($transactions) && $transactions->count() > 0)
                                            @foreach($transactions as $transaction)
                                            <tr>
                                                <td class="px-4 text-nowrap">
                                                    <div class="fw-semibold text-dark">{{ $transaction->created_at->format('d M Y') }}</div>
                                                    <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 250px;" title="{{ $transaction->narration }}">
                                                        {{ $transaction->narration ?? 'Transaction' }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if($transaction->transaction_type == 'add')
                                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Credit</span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Debit</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 text-end fw-bold {{ $transaction->transaction_type == 'add' ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaction->transaction_type == 'add' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    <i class="fas fa-receipt fa-3x mb-3 text-light"></i>
                                                    <p class="mb-0">No transactions found</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="mobile-wrapper d-lg-none">
    <!-- Header Section -->
    <div class="wallet-header">
        <div class="header-top">
            <a href="{{ route('user.profile') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div class="page-title">My Wallet</div>
                <div class="sub-title">Your personal finance hub</div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabs -->
        <div class="custom-tabs">
            <div class="tab-item active" onclick="switchTab('wallet')">Wallet</div>
            <div class="tab-item" onclick="switchTab('addMoney')">Add Money</div>
        </div>

        <!-- Dynamic Balance Display (Shown in Header) -->
        <div class="balance-container">
            <div class="balance-label" id="balanceLabel">Balance</div>
            <div class="balance-amount" id="displayAmount">₹{{ number_format($user->wallet_balance ?? 0, 2) }}</div>
            <a href="#" class="add-note-link" id="addNoteLink" style="display: none;" onclick="showNoteInput(event)">add a note</a>
            <div id="noteInputWrapper" style="display: none; max-width: 200px; margin: 0 auto;">
                <input type="text" id="visibleNoteInput" class="form-control form-control-sm text-center" placeholder="Enter note" style="background: rgba(255,255,255,0.2); border: none; color: white;" onblur="saveNote()">
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        
        <!-- Tab 1: Wallet Transactions -->
        <div id="walletTab" class="tab-content active">
            <div class="transactions-title">Recent Transactions</div>

            <form action="{{ route('user.wallet.show') }}" method="GET" class="row g-2 mb-3">
                <div class="col-6">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                </div>
                <div class="col-6">
                    <a href="{{ route('user.wallet.show') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                </div>
                <div class="col-12">
                    <a href="{{ route('user.wallet.show', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-file-excel me-1"></i> Download Excel
                    </a>
                </div>
            </form>
            
            @if(isset($transactions) && $transactions->count() > 0)
                @foreach($transactions as $transaction)
                    <div class="transaction-item">
                        <div class="trans-icon">
                            @if($transaction->transaction_type == 'add')
                                <i class="fas fa-arrow-down text-success"></i>
                            @else
                                <i class="fas fa-arrow-up text-danger"></i>
                            @endif
                        </div>
                        <div class="trans-details">
                            <div class="trans-title">{{ $transaction->narration ?? 'Transaction' }}</div>
                            <div class="trans-date">{{ $transaction->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <div class="trans-amount {{ $transaction->transaction_type == 'add' ? 'amount-credit' : 'amount-debit' }}">
                            {{ $transaction->transaction_type == 'add' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-transactions">
                    No transactions yet
                </div>
            @endif
        </div>

        <!-- Tab 2: Add Money Keypad -->
        <div id="addMoneyTab" class="tab-content">
            <form action="{{ route('user.wallet.initiate') }}" method="POST" id="addMoneyForm">
                @csrf
                <input type="hidden" name="amount" id="paymentAmount" value="0">
                <input type="hidden" name="note" id="hiddenNoteInput" value="">
                <div class="keypad-container">
                    @if(!empty($isFirstWalletTopup))
                        <div class="text-center small text-muted mb-3">First wallet recharge: choose ₹150 or ₹500.</div>
                        <div class="d-grid gap-2 mb-4">
                            <button type="button" class="btn btn-light py-3 rounded-pill fw-semibold" onclick="setPresetAmount('150')">₹150 (Get ₹50 bonus)</button>
                            <button type="button" class="btn btn-light py-3 rounded-pill fw-semibold" onclick="setPresetAmount('500')">₹500 (Get ₹300 bonus)</button>
                        </div>
                    @else
                        <div class="keypad-grid">
                            <button type="button" class="key-btn" onclick="appendDigit('1')">1</button>
                            <button type="button" class="key-btn" onclick="appendDigit('2')">2</button>
                            <button type="button" class="key-btn" onclick="appendDigit('3')">3</button>
                            <button type="button" class="key-btn" onclick="appendDigit('4')">4</button>
                            <button type="button" class="key-btn" onclick="appendDigit('5')">5</button>
                            <button type="button" class="key-btn" onclick="appendDigit('6')">6</button>
                            <button type="button" class="key-btn" onclick="appendDigit('7')">7</button>
                            <button type="button" class="key-btn" onclick="appendDigit('8')">8</button>
                            <button type="button" class="key-btn" onclick="appendDigit('9')">9</button>
                            <button type="button" class="key-btn" onclick="appendDigit('.')">.</button>
                            <button type="button" class="key-btn" onclick="appendDigit('0')">0</button>
                            <button type="button" class="key-btn" onclick="backspace()"><i class="fas fa-backspace" style="font-size: 18px;"></i></button>
                        </div>
                    @endif
                    
                    <button type="submit" class="pay-btn">Pay Now</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@include('user.partials.theme-script')
<script>
    let currentTab = 'wallet';
    let currentAmount = '0';
    const originalBalance = "{{ number_format($user->wallet_balance ?? 0, 2) }}";
    const isFirstWalletTopup = {{ !empty($isFirstWalletTopup) ? 'true' : 'false' }};

    function switchTab(tab) {
        currentTab = tab;
        
        // Update Tab Styles
        document.querySelectorAll('.tab-item').forEach(el => el.classList.remove('active'));
        if(tab === 'wallet') {
            document.querySelectorAll('.tab-item')[0].classList.add('active');
            document.getElementById('walletTab').classList.add('active');
            document.getElementById('addMoneyTab').classList.remove('active');
            
            // Reset display to wallet balance
            document.getElementById('balanceLabel').innerText = 'Balance';
            document.getElementById('displayAmount').innerText = '₹' + originalBalance;
            document.getElementById('addNoteLink').style.display = 'none';
            document.getElementById('noteInputWrapper').style.display = 'none';
        } else {
            document.querySelectorAll('.tab-item')[1].classList.add('active');
            document.getElementById('walletTab').classList.remove('active');
            document.getElementById('addMoneyTab').classList.add('active');
            
            // Reset input amount
            currentAmount = isFirstWalletTopup ? '150' : '0';
            updateAmountDisplay();
            document.getElementById('balanceLabel').innerText = '';
            
            // Reset note
            document.getElementById('hiddenNoteInput').value = '';
            document.getElementById('visibleNoteInput').value = '';
            document.getElementById('addNoteLink').innerText = 'add a note';
            
            document.getElementById('addNoteLink').style.display = 'block';
            document.getElementById('noteInputWrapper').style.display = 'none';
        }
    }

    function showNoteInput(e) {
        e.preventDefault();
        document.getElementById('addNoteLink').style.display = 'none';
        document.getElementById('noteInputWrapper').style.display = 'block';
        document.getElementById('visibleNoteInput').focus();
    }

    function saveNote() {
        const note = document.getElementById('visibleNoteInput').value;
        document.getElementById('hiddenNoteInput').value = note;
        
        if (note.trim() !== '') {
            document.getElementById('addNoteLink').innerText = note;
        } else {
            document.getElementById('addNoteLink').innerText = 'add a note';
        }
        
        document.getElementById('noteInputWrapper').style.display = 'none';
        document.getElementById('addNoteLink').style.display = 'block';
    }

    function appendDigit(digit) {
        if (currentTab !== 'addMoney') return;
        if (isFirstWalletTopup) return;
        
        if (currentAmount === '0' && digit !== '.') {
            currentAmount = digit;
        } else {
            // Prevent multiple dots
            if (digit === '.' && currentAmount.includes('.')) return;
            currentAmount += digit;
        }
        updateAmountDisplay();
    }

    function backspace() {
        if (currentTab !== 'addMoney') return;
        if (isFirstWalletTopup) return;
        
        if (currentAmount.length > 1) {
            currentAmount = currentAmount.slice(0, -1);
        } else {
            currentAmount = '0';
        }
        updateAmountDisplay();
    }

    function setPresetAmount(amount) {
        if (currentTab !== 'addMoney') return;
        currentAmount = String(amount);
        updateAmountDisplay();
    }

    function updateAmountDisplay() {
        document.getElementById('displayAmount').innerText = '₹' + currentAmount;
        document.getElementById('paymentAmount').value = currentAmount;
    }
</script>
@include('user.partials.theme-script')
</body>
</html>
