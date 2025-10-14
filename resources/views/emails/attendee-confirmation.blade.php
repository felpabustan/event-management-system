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

Check-in Token: {{ $token }}

**Important:** This Check-in Token code is unique to your registration. Please do not share it with others.

We look forward to seeing you at the event!

If you have any questions, please don't hesitate to contact us.

Best regards,<br>
{{ config('app.name') }} Team
</x-mail::message>
