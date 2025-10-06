<x-mail::message>
# Event Registration Confirmation

Hello {{ $registration->name }},

Thank you for registering for **{{ $event->title }}**!

## Event Details

**Date:** {{ $event->date->format('l, F j, Y') }}  
**Time:** {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}  
**Venue:** {{ $event->venue }}

**Description:**  
{{ $event->description }}

## Your Registration Details

**Name:** {{ $registration->name }}  
**Email:** {{ $registration->email }}  
@if($registration->phone)
**Phone:** {{ $registration->phone }}  
@endif

## Check-in QR Code

Please bring this QR code with you to the event for quick check-in:

<div style="text-align: center; margin: 20px 0;">
    <img src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" alt="Check-in QR Code" style="width: 200px; height: 200px;">
</div>
Check-in Token: {{ $token }}

**Important:** This QR code is unique to your registration. Please do not share it with others.

We look forward to seeing you at the event!

If you have any questions, please don't hesitate to contact us.

Best regards,<br>
{{ config('app.name') }} Team
</x-mail::message>
