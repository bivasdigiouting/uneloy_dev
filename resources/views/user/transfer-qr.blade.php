@extends('user.layouts.app')

@section('title', 'QR to QR Transfer - UOnly')

@push('styles')
<style>
    /* Match dashboard background */
    body { background-color: var(--bg-light); color: var(--text-dark); }

    /* Match dashboard navbar gradient - handled by global theme styles */
    /* .navbar.bg-dark {
        background: var(--primary-gradient) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */

    .card { border: none; border-radius: 14px; box-shadow: 0 6px 20px rgba(13,110,253,.12); background-color: var(--card-bg); color: var(--text-dark); }
    .form-control { border-radius: 10px; background-color: var(--bg-light); color: var(--text-dark); border: 1px solid var(--muted-text); }
    .page-header { margin-bottom: 1rem; }
    .btn-primary-custom { background: var(--primary-gradient); border: none; color: white; }
    .btn-primary-custom:hover { opacity: 0.9; color: white; }
    .qr-reader { width: 100%; border-radius: 12px; overflow: hidden; background: rgba(15, 23, 42, 0.06); }
    .qr-scan-actions .btn { border-radius: 10px; }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="page-header">
        <h4 class="mb-2"><i class="fa-solid fa-qrcode me-2"></i>QR to QR Transfer</h4>
        <p class="text-muted mb-0">Send funds securely by scanning or pasting a QR.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-block d-md-none mb-3">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <div class="fw-semibold">Scan QR</div>
                            <div class="qr-scan-actions d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary-custom" id="qrStartBtn">Start</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="qrStopBtn">Stop</button>
                            </div>
                        </div>
                        <div id="qr-reader" class="qr-reader"></div>
                        <div id="qrScanStatus" class="small text-muted mt-2"></div>
                        <div id="qrScanMessage" class="alert d-none mt-2 mb-0" role="alert"></div>
                    </div>
                    <form action="{{ route('user.wallet.transfer.qr.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Recipient QR Code</label>
                            <input type="text" id="toQrInput" name="to_qr" value="{{ old('to_qr') }}" class="form-control" placeholder="Paste QR string or ID" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount (₹)</label>
                            <input type="number" id="amountInput" step="0.01" min="1" name="amount" value="{{ old('amount') }}" class="form-control" placeholder="Enter amount" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note (optional)</label>
                            <input type="text" name="note" value="{{ old('note') }}" class="form-control" placeholder="Message to recipient">
                        </div>
                        <button type="submit" class="btn btn-primary-custom"><i class="fa-solid fa-paper-plane me-1"></i>Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.10/html5-qrcode.min.js"></script>
<script>
    (function () {
        function isLikelyMobile() {
            const coarse = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
            const smallScreen = window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches;
            return Boolean(coarse || smallScreen);
        }

        function setMessage(message, type) {
            const el = document.getElementById('qrScanMessage');
            if (!el) {
                return;
            }
            if (!message) {
                el.classList.add('d-none');
                el.textContent = '';
                el.classList.remove('alert-success', 'alert-danger', 'alert-info');
                return;
            }
            el.textContent = message;
            el.classList.remove('d-none');
            el.classList.remove('alert-success', 'alert-danger', 'alert-info');
            el.classList.add(type === 'success' ? 'alert-success' : type === 'danger' ? 'alert-danger' : 'alert-info');
        }

        function setStatus(text) {
            const el = document.getElementById('qrScanStatus');
            if (el) {
                el.textContent = text || '';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (!isLikelyMobile()) {
                return;
            }

            const startBtn = document.getElementById('qrStartBtn');
            const stopBtn = document.getElementById('qrStopBtn');
            const toQrInput = document.getElementById('toQrInput');
            const amountInput = document.getElementById('amountInput');

            if (!startBtn || !stopBtn || !toQrInput) {
                return;
            }

            if (!navigator.mediaDevices || typeof navigator.mediaDevices.getUserMedia !== 'function') {
                setMessage('Camera access is not available on this device/browser.', 'danger');
                return;
            }

            if (typeof window.Html5Qrcode !== 'function') {
                setMessage('QR scanner failed to load. Please try again.', 'danger');
                return;
            }

            let scanner = null;
            let running = false;

            async function stopScanner() {
                if (!scanner || !running) {
                    return;
                }
                try {
                    await scanner.stop();
                    await scanner.clear();
                } finally {
                    running = false;
                    stopBtn.classList.add('d-none');
                    startBtn.classList.remove('d-none');
                    setStatus('');
                }
            }

            async function startScanner() {
                setMessage('', 'info');
                setStatus('Starting camera…');

                try {
                    scanner = scanner || new window.Html5Qrcode('qr-reader');
                    running = true;
                    startBtn.classList.add('d-none');
                    stopBtn.classList.remove('d-none');

                    await scanner.start(
                        { facingMode: 'environment' },
                        { fps: 10, qrbox: { width: 240, height: 240 } },
                        async function (decodedText) {
                            const value = String(decodedText || '').trim();
                            if (!value) {
                                return;
                            }

                            toQrInput.value = value;
                            setMessage('QR captured successfully.', 'success');
                            setStatus('');

                            if (navigator.vibrate) {
                                navigator.vibrate(100);
                            }

                            await stopScanner();

                            if (amountInput) {
                                amountInput.focus();
                            } else {
                                toQrInput.focus();
                            }
                        },
                        function () {}
                    );

                    setStatus('Point the camera at a QR code…');
                } catch (err) {
                    running = false;
                    stopBtn.classList.add('d-none');
                    startBtn.classList.remove('d-none');
                    setStatus('');
                    setMessage('Unable to start the camera. Please allow camera permission and try again.', 'danger');
                }
            }

            startBtn.addEventListener('click', startScanner);
            stopBtn.addEventListener('click', stopScanner);

            startScanner();

            window.addEventListener('beforeunload', function () {
                if (running) {
                    stopScanner();
                }
            });
        });
    })();
</script>
@endpush
