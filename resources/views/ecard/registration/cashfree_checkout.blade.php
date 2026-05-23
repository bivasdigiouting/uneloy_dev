<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment...</title>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h3>Redirecting to Payment Gateway...</h3>
        <p>Please do not close this window.</p>
    </div>

    <script>
        const cashfree = Cashfree({
            mode: "{{ $environment }}"
        });
        cashfree.checkout({
            paymentSessionId: "{{ $payment_session_id }}",
            redirectTarget: "_self"
        });
    </script>
</body>
</html>