<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Cancelled</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .event-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .alert {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        h1 { color: #1f2937; margin-bottom: 20px; }
        h2 { color: #374151; margin-bottom: 15px; }
        .detail-row { margin-bottom: 8px; }
        .label { font-weight: 600; color: #4b5563; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Registration Cancelled</h1>
    </div>

    <div class="content">
        <p>Dear {{ $registrationData['name'] }},</p>

        <p>We regret to inform you that your registration for the following event has been cancelled:</p>

        <div class="event-details">
            <h2>Event Details</h2>
            <div class="detail-row">
                <span class="label">Event:</span> {{ $registrationData['event_title'] }}
            </div>
            <div class="detail-row">
                <span class="label">Date:</span> {{ $registrationData['event_date'] }}
            </div>
            <div class="detail-row">
                <span class="label">Time:</span> {{ $registrationData['event_time'] }}
            </div>
            <div class="detail-row">
                <span class="label">Venue:</span> {{ $registrationData['event_venue'] }}
            </div>
            @if($registrationData['is_paid_event'] && $registrationData['payment_status'] === 'paid')
                <div class="detail-row">
                    <span class="label">Amount Paid:</span> {{ $registrationData['price'] }}
                </div>
            @endif
        </div>

        @if($registrationData['is_paid_event'] && $registrationData['payment_status'] === 'paid')
            <div class="alert">
                <h2>Refund Information</h2>
                <p><strong>Your refund will be processed within 5-10 business days.</strong></p>
                <p>The refund will be credited back to your original payment method. You will receive a separate notification from our payment processor once the refund has been completed.</p>
            </div>
        @endif

        <p>We sincerely apologize for any inconvenience this cancellation may cause. If you have any questions or concerns, please don't hesitate to contact our support team.</p>

        <div class="detail-row" style="margin-top: 20px;">
            <span class="label">Cancelled on:</span> {{ $registrationData['cancellation_date'] }}
        </div>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>If you need assistance, please contact our support team.</p>
    </div>
</body>
</html>