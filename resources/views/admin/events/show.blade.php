<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.checkin.scanner', $event) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11a9 9 0 11-18 0 9 9 0 0118 0zm-9 8a4.5 4.5 0 00-.08-2.25M12 13a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                    QR Check-in
                </a>
                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Event
                </a>
                @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('events.export', $event) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Export CSV
                    </a>
                @endif
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Back to Events
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Event Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Event Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $event->date->format('l, F j, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Time</dt>
                                    <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Venue</dt>
                                    <dd class="text-sm text-gray-900">{{ $event->venue }}</dd>
                                </div>
                                @if($event->category)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                                        <dd class="text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $event->category->color }}"></div>
                                                <span class="font-medium">{{ $event->category->name }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Max {{ $event->category->max_registrations_per_user }} registrations per user
                                            </div>
                                        </dd>
                                    </div>
                                @endif
                                <div class="w-full">
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="text-sm text-gray-900">{{ Str::limit($event->description, 200) }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($event->is_paid)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Paid Event</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Free Event</span>
                                        @endif
                                    </dd>
                                </div>
                                @if($event->is_paid)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Price</dt>
                                        <dd class="text-sm text-gray-900">{{ $event->getFormattedPriceAttribute() }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
                                        <dd class="text-sm text-gray-900">
                                            {{ strtoupper($event->currency) }} {{ number_format($registrations->where('payment_status', 'paid')->count() * $event->price, 2) }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Paid Registrations</dt>
                                        <dd class="text-sm text-gray-900">{{ $registrations->where('payment_status', 'paid')->count() }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Registration Status</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Registrations</dt>
                                    <dd class="text-sm text-gray-900">{{ $event->current_capacity }} / {{ $event->max_capacity }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Available Spots</dt>
                                    <dd class="text-sm text-gray-900">{{ $event->availableSpots() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        @if($event->isFull())
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Full</span>
                                        @elseif($event->date < now()->toDateString())
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Past Event</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Open for Registration</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Capacity Progress</dt>
                                    <dd>
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $event->max_capacity > 0 ? ($event->current_capacity / $event->max_capacity) * 100 : 0 }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $event->max_capacity > 0 ? round(($event->current_capacity / $event->max_capacity) * 100, 1) : 0 }}% filled</p>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Check-in Status</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Checked In</dt>
                                    <dd class="text-sm text-gray-900">{{ $registrations->where('checked_in', true)->count() }} / {{ $registrations->count() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Not Checked In</dt>
                                    <dd class="text-sm text-gray-900">{{ $registrations->where('checked_in', false)->count() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Check-in Rate</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $registrations->count() > 0 ? round(($registrations->where('checked_in', true)->count() / $registrations->count()) * 100, 1) : 0 }}%
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Check-in Progress</dt>
                                    <dd>
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $registrations->count() > 0 ? ($registrations->where('checked_in', true)->count() / $registrations->count()) * 100 : 0 }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $registrations->count() > 0 ? round(($registrations->where('checked_in', true)->count() / $registrations->count()) * 100, 1) : 0 }}% checked in</p>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registrations List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Registrations ({{ $registrations->count() }})</h3>
                        @if($registrations->count() > 0 && Auth::user()->isSuperAdmin())
                            <a href="{{ route('events.export', $event) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </a>
                        @endif
                    </div>

                    @if($registrations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                                        @if(Auth::user()->isSuperAdmin())
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($registrations as $registration)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $registration->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $registration->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $registration->phone ?? 'Not provided' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($registration->payment_status === 'paid')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                                @elseif($registration->payment_status === 'free')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Free</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($registration->payment_status) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($registration->checked_in)
                                                    <div>
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Checked In</span>
                                                        <div class="text-xs text-gray-500 mt-1">{{ $registration->checked_in_at->format('M j, g:i A') }}</div>
                                                    </div>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Not Checked In</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $registration->created_at->format('M j, Y g:i A') }}</div>
                                            </td>
                                            @if(Auth::user()->isSuperAdmin())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <!-- Check-in/Undo Check-in Button -->
                                                        @if($registration->checked_in)
                                                            <form action="{{ route('admin.checkin.undo', $registration) }}" 
                                                                  method="POST" 
                                                                  class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="text-orange-600 hover:text-orange-900 text-sm font-medium"
                                                                        title="Undo Check-in">
                                                                    Undo Check-in
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('admin.checkin.manual', $registration) }}" 
                                                                  method="POST" 
                                                                  class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="text-green-600 hover:text-green-900 text-sm font-medium"
                                                                        title="Manual Check-in">
                                                                    Check In
                                                                </button>
                                                            </form>
                                                        @endif
                                                        
                                                        <!-- Delete Button -->
                                                        <form action="{{ route('admin.registrations.destroy', $registration) }}" 
                                                              method="POST" 
                                                              class="inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this registration?{{ $event->is_paid && $registration->payment_status === 'paid' ? ' A refund will be automatically processed.' : '' }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                                    title="Delete Registration{{ $event->is_paid && $registration->payment_status === 'paid' ? ' & Process Refund' : '' }}">
                                                                Delete
                                                                @if($event->is_paid && $registration->payment_status === 'paid')
                                                                    & Refund
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No registrations yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Attendees will appear here once they register for this event.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>