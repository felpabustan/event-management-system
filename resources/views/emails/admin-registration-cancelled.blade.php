<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Alert: Registration Cancelled</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #ffffff;
            padding: 25px;
            margin-bottom: 20px;
            text-align: center;
            border-top: 3px solid #dc2626;
        }
        .content {
            background-color: #ffffff;
            padding: 25px;
            border: 1px solid #e9ecef;
        }
        .section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f3f4;
        }
        .section:last-child {
            border-bottom: none;
        }
        .action-required {
            background-color: #fff8f1;
            border: 1px solid #fed7aa;
            padding: 20px;
            margin: 25px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        h1 { 
            color: #1f2937; 
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        h2 { 
            color: #374151; 
            margin: 0 0 15px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .detail-row { 
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }
        .label { 
            font-weight: 600; 
            color: #4b5563;
            min-width: 140px;
            margin-right: 10px;
        }
        .value {
            color: #1f2937;
        }
        .important {
            background-color: #fef3c7;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .code {
            background-color: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 13px;
            word-break: break-all;
        }
        .steps {
            margin-top: 15px;
        }
        .steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .steps li {
            margin-bottom: 8px;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Registration Cancellation Notice</h1>
        <p style="margin: 10px 0 0 0; color: #6b7280;">Admin Notification</p>
    </div>

    <div class="content">
        <p style="margin-bottom: 25px;"><strong>A registration has been cancelled by {{ $registrationData['cancelled_by'] }}.</strong></p>

        <div class="section">
            <h2>Registrant Details</h2>
            <div class="detail-row">
                <span class="label">Name:</span>
                <span class="value important">{{ $registrationData['name'] }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Email:</span>
                <span class="value important">{{ $registrationData['email'] }}</span>
            </div>
            @if($registrationData['phone'])
                <div class="detail-row">
                    <span class="label">Phone:</span>
                    <span class="value">{{ $registrationData['phone'] }}</span>
                </div>
            @endif
            <div class="detail-row">
                <span class="label">Payment Status:</span>
                <span class="value important">{{ ucfirst($registrationData['payment_status']) }}</span>
            </div>
        </div>

        <div class="section">
            <h2>Event Information</h2>
            <div class="detail-row">
                <span class="label">Event:</span>
                <span class="value">{{ $registrationData['event_title'] }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Date:</span>
                <span class="value">{{ $registrationData['event_date'] }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Time:</span>
                <span class="value">{{ $registrationData['event_time'] }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Venue:</span>
                <span class="value">{{ $registrationData['event_venue'] }}</span>
            </div>
            @if($registrationData['is_paid_event'])
                <div class="detail-row">
                    <span class="label">Event Price:</span>
                    <span class="value">{{ $registrationData['price'] }}</span>
                </div>
            @endif
        </div>

        @if($registrationData['is_paid_event'] && $registrationData['payment_status'] === 'paid')
            <div class="action-required">
                <h2>Action Required: Manual Refund Processing</h2>
                <p><strong>This paid registration requires a refund to be processed manually in Stripe Dashboard.</strong></p>
                
                <div style="margin-top: 20px;">
                    <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #374151;">Stripe Reference Information</h3>
                    <div class="detail-row">
                        <span class="label">Customer Name:</span>
                        <span class="value important">{{ $registrationData['name'] }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Customer Email:</span>
                        <span class="value important">{{ $registrationData['email'] }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Refund Amount:</span>
                        <span class="value important">{{ $registrationData['price'] }}</span>
                    </div>
                    @if($registrationData['stripe_session_id'])
                        <div class="detail-row">
                            <span class="label">Session ID:</span>
                            <span class="value code">{{ $registrationData['stripe_session_id'] }}</span>
                        </div>
                    @endif
                    <div class="detail-row">
                        <span class="label">Event Reference:</span>
                        <span class="value">{{ $registrationData['event_title'] }} - {{ $registrationData['event_date'] }}</span>
                    </div>
                </div>

                <div class="steps">
                    <p style="margin: 15px 0 5px 0; font-weight: 600; color: #374151;">Refund Processing Steps:</p>
                    <ol>
                        <li>Access Stripe Dashboard</li>
                        <li>Search for payment using customer email or session ID</li>
                        <li>Process full refund for: {{ $registrationData['price'] }}</li>
                        <li>Add note: "Registration cancelled by admin - {{ $registrationData['event_title'] }}"</li>
                    </ol>
                </div>
            </div>
        @endif

        <div class="section">
            <h2>Cancellation Details</h2>
            <div class="detail-row">
                <span class="label">Cancelled by:</span>
                <span class="value">{{ $registrationData['cancelled_by'] }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Cancellation Date:</span>
                <span class="value">{{ $registrationData['cancellation_date'] }}</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Event Management System - Automated Admin Notification</p>
        @if($registrationData['is_paid_event'] && $registrationData['payment_status'] === 'paid')
            <p style="color: #dc2626; font-weight: 600; margin-top: 10px;">Please process the refund in Stripe Dashboard</p>
        @endif
    </div>
</body>
</html>