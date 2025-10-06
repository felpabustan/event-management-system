<x-mail::message>
# New Event Registration

A new attendee has registered for **{{ $event->title }}**.

## Event Details

**Event:** {{ $event->title }}  
**Date:** {{ $event->date->format('l, F j, Y') }}  
**Time:** {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}  
**Venue:** {{ $event->venue }}

## Attendee Information

**Name:** {{ $registration->name }}  
**Email:** {{ $registration->email }}  
**Phone:** {{ $registration->phone }}  
**Registration Date:** {{ $registration->created_at->format('M j, Y \a\t g:i A') }}

## Current Event Status

**Current Capacity:** {{ $event->current_capacity }} / {{ $event->max_capacity }}  
**Available Spots:** {{ $event->availableSpots() }}

<x-mail::button :url="route('events.show', $event)">
View Event Details
</x-mail::button>

Best regards,<br>
{{ config('app.name') }} System
</x-mail::message>
