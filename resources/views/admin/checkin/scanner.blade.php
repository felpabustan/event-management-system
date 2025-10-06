<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event Check-in: ') . $event->title }}
            </h2>
            <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
            <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Registrations</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $totalRegistrations }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Checked In</dt>
                                    <dd class="text-lg font-medium text-green-600">{{ $checkedInCount }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Not Checked In</dt>
                                    <dd class="text-lg font-medium text-red-600">{{ $totalRegistrations - $checkedInCount }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- QR Scanner -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">QR Code Scanner</h3>
                        <p class="text-sm text-gray-600">Scan attendee QR codes for quick check-in</p>
                    </div>
                    <div class="p-6">
                        <!-- Camera selector -->
                        <div class="mb-4">
                            <label for="cameraSelect" class="block text-sm font-medium text-gray-700 mb-2">Select Camera:</label>
                            <select id="cameraSelect" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Loading cameras...</option>
                            </select>
                        </div>

                        <!-- Scanner container -->
                        <div class="relative">
                            <div id="qr-reader" class="w-full"></div>
                            <div id="qr-reader-results" class="mt-4"></div>
                        </div>

                        <!-- Manual token input -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Manual Token Entry</h4>
                            <div class="flex space-x-2">
                                <input type="text" id="manual-token" placeholder="Enter QR token manually" class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button onclick="processManualToken()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Check In
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Check-ins -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Check-ins</h3>
                    </div>
                    <div class="p-6">
                        <div id="recent-checkins" class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($registrations->where('checked_in', true)->take(10) as $registration)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $registration->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $registration->email }}</p>
                                        <p class="text-xs text-gray-500">{{ $registration->checked_in_at->format('g:i A') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Checked In
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No check-ins yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration List -->
            <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">All Registrations</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registrations as $registration)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $registration->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $registration->email }}</div>
                                        @if($registration->phone)
                                            <div class="text-sm text-gray-500">{{ $registration->phone }}</div>
                                        @endif
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
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $registration->checked_in_at->format('M j, g:i A') }}
                                                    @if($registration->checkedInBy)
                                                        <br>by {{ $registration->checkedInBy->name }}
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Not Checked In</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @if($registration->checked_in)
                                            <form class="inline" action="{{ route('admin.checkin.undo', $registration) }}" method="POST" onsubmit="return confirm('Are you sure you want to undo this check-in?')">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Undo Check-in</button>
                                            </form>
                                        @else
                                            <form class="inline" action="{{ route('admin.checkin.manual', $registration) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Manual Check-in</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrcodeScanner;
        let currentCameraId;

        // Initialize the QR scanner
        function initializeScanner() {
            // Check if we're in a secure context
            if (!window.isSecureContext && location.hostname !== 'localhost') {
                document.getElementById('cameraSelect').innerHTML = '<option>Camera access requires HTTPS or localhost</option>';
                document.getElementById('qr-reader').innerHTML = `
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        <h4 class="font-bold">Camera Access Restricted</h4>
                        <p class="text-sm mt-2">Camera access is only supported in secure contexts (HTTPS) or when running on localhost.</p>
                        <p class="text-sm mt-1"><strong>Solutions:</strong></p>
                        <ul class="text-sm mt-1 ml-4 list-disc">
                            <li>Access the site via <code>http://localhost:8000</code> instead of <code>http://127.0.0.1:8000</code></li>
                            <li>Use the manual token entry below</li>
                            <li>Set up HTTPS for production</li>
                        </ul>
                    </div>
                `;
                return;
            }

            // Get available cameras
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraSelect = document.getElementById('cameraSelect');
                    cameraSelect.innerHTML = '';
                    
                    devices.forEach((device, index) => {
                        const option = document.createElement('option');
                        option.value = device.id;
                        option.text = device.label || `Camera ${index + 1}`;
                        cameraSelect.appendChild(option);
                    });

                    // Use the first camera by default
                    currentCameraId = devices[0].id;
                    cameraSelect.value = currentCameraId;
                    startScanner(currentCameraId);

                    // Handle camera selection change
                    cameraSelect.addEventListener('change', function() {
                        if (html5QrcodeScanner) {
                            html5QrcodeScanner.stop().then(() => {
                                startScanner(this.value);
                            });
                        }
                    });
                } else {
                    document.getElementById('cameraSelect').innerHTML = '<option>No cameras found</option>';
                    document.getElementById('qr-reader').innerHTML = `
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                            <h4 class="font-bold">No Cameras Detected</h4>
                            <p class="text-sm mt-2">No cameras were found on this device. Please use the manual token entry below.</p>
                        </div>
                    `;
                }
            }).catch(err => {
                console.error('Error getting cameras:', err);
                document.getElementById('cameraSelect').innerHTML = '<option>Error loading cameras</option>';
                
                let errorMessage = 'Unknown error occurred';
                if (err.message && err.message.includes('secure context')) {
                    errorMessage = 'Camera access requires HTTPS or localhost';
                } else if (err.message) {
                    errorMessage = err.message;
                }
                
                document.getElementById('qr-reader').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <h4 class="font-bold">Camera Access Error</h4>
                        <p class="text-sm mt-2">${errorMessage}</p>
                        <p class="text-sm mt-2"><strong>Solutions:</strong></p>
                        <ul class="text-sm mt-1 ml-4 list-disc">
                            <li>Access the site via <code>http://localhost:8000</code></li>
                            <li>Grant camera permissions when prompted</li>
                            <li>Use the manual token entry below</li>
                            <li>Try refreshing the page</li>
                        </ul>
                    </div>
                `;
            });
        }

        function startScanner(cameraId) {
            currentCameraId = cameraId;
            html5QrcodeScanner = new Html5Qrcode("qr-reader");
            
            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrcodeScanner.start(
                cameraId,
                config,
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error('Unable to start scanning:', err);
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // The QR code contains just the token
            let token = decodedText.trim();
            
            // Validate token format (should be 32 characters)
            if (token.length !== 32) {
                showResult('error', 'Invalid QR code format');
                return;
            }

            processQRToken(token);
        }

        function onScanError(errorMessage) {
            // Handle scan error silently
        }

        function processQRToken(token) {
            console.log('Processing QR token:', token);
            
            fetch(`{{ route('admin.checkin.verify', $event) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ token: token })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showResult('success', data.message, data.registration);
                    updateRecentCheckins(data.registration);
                    // Optionally stop scanner for a moment to avoid duplicate scans
                    setTimeout(() => {
                        document.getElementById('qr-reader-results').innerHTML = '';
                    }, 3000);
                } else {
                    showResult('error', data.message, data.registration);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showResult('error', 'Network error occurred');
            });
        }

        function processManualToken() {
            const token = document.getElementById('manual-token').value.trim();
            if (token) {
                processQRToken(token);
                document.getElementById('manual-token').value = '';
            }
        }

        function showResult(type, message, registration = null) {
            const resultsDiv = document.getElementById('qr-reader-results');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            
            let content = `
                <div class="border ${bgColor} px-4 py-3 rounded relative">
                    <span class="block sm:inline">${message}</span>
            `;
            
            if (registration) {
                content += `
                    <div class="mt-2 text-sm">
                        <strong>Name:</strong> ${registration.name}<br>
                        <strong>Email:</strong> ${registration.email}
                    </div>
                `;
            }
            
            content += '</div>';
            resultsDiv.innerHTML = content;
        }

        function updateRecentCheckins(registration) {
            const recentDiv = document.getElementById('recent-checkins');
            const newCheckin = `
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">${registration.name}</p>
                        <p class="text-sm text-gray-600">${registration.email}</p>
                        <p class="text-xs text-gray-500">Just now</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Checked In
                        </span>
                    </div>
                </div>
            `;
            recentDiv.insertAdjacentHTML('afterbegin', newCheckin);
            
            // Remove oldest if more than 10
            const checkins = recentDiv.children;
            if (checkins.length > 10) {
                recentDiv.removeChild(checkins[checkins.length - 1]);
            }
            
            // Update stats
            location.reload(); // Simple way to update stats
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeScanner();
        });

        // Handle manual token input on Enter key
        document.getElementById('manual-token').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                processManualToken();
            }
        });
    </script>
    @endpush
</x-app-layout>