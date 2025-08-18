<x-layout>

    <x-slot:heading>

        Visitor Management
    </x-slot:heading>
    <div class="lg:h-[calc(100vh-10rem)] h-[calc(100vh-6.5rem)] overflow-auto scrollbar-hidden m-auto w-full p-10">

        <div class="w-full m-auto max-w-4xl h-full space-y-6 mb-10">
            <div class="flex flex-col items-start w-full">
                <div class="flex flex-col sm:flex-row sm:items-center w-full justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2 sm:mb-0">Visit Details</h2>
                    
                    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id , 'visits', 'create'))
                        <a 
                            href="{{ route('visitor.departure', ['visitor' => $visitor->id]) }}"
                            class="flex items-center gap-2 bg-gradient-to-b from-blue-500 to-blue-600 px-4 py-2 rounded-lg text-white font-medium hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg"
                        >
                            <svg class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Continue to sign out
                        </a>
                    @endif
                </div>
                
                <div class="w-full grid grid-cols-2 gap-6">
                    {{-- Visitor Information --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="border-b border-gray-200 bg-gray-50 p-4">
                            <h3 class="text-xl font-semibold text-gray-800">Visitor Information</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Visitor:</span>
                                <span class="font-semibold">{{ $visitor->full_name }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Email:</span>
                                <span>{{ $visitor->email }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Phone:</span>
                                <span>{{ str_replace('233', '0', $visitor->phone_number) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Visitee:</span>
                                <span>{{ $visitor->visitee->first_name }} {{ $visitor->visitee->last_name }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Company:</span>
                                <span>{{ $visitor->company_name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Visit Details --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="border-b border-gray-200 bg-gray-50 p-4">
                            <h3 class="text-xl font-semibold text-gray-800">Visit Details</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Purpose:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium capitalize
                                    @if($visitor->purpose == 'personal') bg-green-100 text-green-700
                                    @elseif($visitor->purpose == 'interview') bg-amber-100 text-amber-700
                                    @elseif($visitor->purpose == 'official') bg-red-100 text-red-700
                                    @else bg-blue-100 text-blue-700
                                    @endif"
                                >
                                    {{ $visitor->purpose }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Arrived:</span>
                                <span class="font-semibold">{{ $visitor->created_at->format('D, d F Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                <span class="text-gray-500 font-medium">Departed:</span>
                                <span class="{{ !$visitor->departed_at ? 'text-blue-600 font-medium' : '' }}">
                                    {{ $visitor->departed_at ? $visitor->departed_at->format('D, d F Y H:i') : 'Visit ongoing' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Devices --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="border-b border-gray-200 bg-gray-50 p-4">
                            <h3 class="text-xl font-semibold text-gray-800">Devices</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($visitor->devices as $device)
                                <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <span class="text-gray-500 font-medium">{{ $device['name'] }}:</span>
                                    <span class="{{ !$device['serial'] ? 'text-red-500' : '' }}">
                                        {{ $device['serial'] ?? 'Did not bring any device' }}
                                    </span>
                                </div>
                            @empty
                                <div class="p-4 text-gray-500">No devices registered.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Companions --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="border-b border-gray-200 bg-gray-50 p-4">
                            <h3 class="text-xl font-semibold text-gray-800">Companions</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($visitor->companions as $companion)
                                <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">

                                    <span class="{{ !$companion['name'] ? 'text-red-500' : 'font-medium uppercase' }}">
                                        {{ $companion['name'] }}
                                    </span>
                                    <span class="{{ !$companion['phone_number'] ? 'text-red-500' : 'font-medium uppercase' }}">
                                        {{ $companion['phone_number'] ?? 'Came alone' }}
                                    </span>
                                </div>
                            @empty
                                <div class="p-4 text-gray-500">No companions.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Access Cards --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="border-b border-gray-200 bg-gray-50 p-4">
                            <h3 class="text-xl font-semibold text-gray-800">Access Cards</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($access_cards as $card)
                                <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <span class="text-gray-500 font-medium">Card Number:</span>
                                    <span class="font-semibold">{{ $card->card_number }}</span>
                                </div>
                            @empty
                                <div class="p-4 text-gray-500">No cards assigned.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ url()->previous() }}" 
                   class="px-4 py-2 text-base font-medium text-blue-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Back
                </a>
            </div>
        </div>
    </div>
</x-layout>