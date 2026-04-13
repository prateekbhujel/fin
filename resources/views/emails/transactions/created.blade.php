@php($companyName = setting('app.company_name', config('app.name')))
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Notification</title>
</head>
<body style="margin:0;padding:24px;background:#f8fafc;font-family:Outfit,Arial,sans-serif;color:#0f172a;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:24px;overflow:hidden;">
        <div style="padding:24px 28px;background:linear-gradient(135deg,#405cf5,#22316f);color:#ffffff;">
            <div style="font-size:13px;letter-spacing:0.24em;text-transform:uppercase;opacity:0.8;">New Transaction Alert</div>
            <h1 style="margin:14px 0 0;font-size:28px;line-height:1.2;">{{ ucfirst($transaction->type) }} transaction recorded</h1>
        </div>

        <div style="padding:28px;">
            <p style="margin-top:0;font-size:15px;line-height:1.7;">
                A new transaction has been added in {{ $companyName }}.
            </p>

            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td style="padding:10px 0;color:#64748b;">Title</td>
                    <td style="padding:10px 0;font-weight:600;text-align:right;">{{ $transaction->title }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;">Amount</td>
                    <td style="padding:10px 0;font-weight:600;text-align:right;">{{ npr($transaction->amount) }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;">Category</td>
                    <td style="padding:10px 0;font-weight:600;text-align:right;">{{ $transaction->category?->name ?? 'Uncategorized' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;">Transaction Date</td>
                    <td style="padding:10px 0;font-weight:600;text-align:right;">
                        {{ optional($transaction->transaction_date)->format('Y-m-d') }}
                        @if ($transaction->transaction_bs)
                            <div style="font-size:12px;color:#64748b;">BS: {{ $transaction->transaction_bs }}</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;">Created By</td>
                    <td style="padding:10px 0;font-weight:600;text-align:right;">{{ $transaction->user?->name }}</td>
                </tr>
            </table>

            @if ($transaction->notes)
                <div style="margin-top:18px;padding:16px;border-radius:16px;background:#f8fafc;border:1px solid #e2e8f0;">
                    <div style="font-size:12px;letter-spacing:0.2em;text-transform:uppercase;color:#64748b;">Notes</div>
                    <p style="margin:8px 0 0;font-size:14px;line-height:1.6;">{{ $transaction->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
