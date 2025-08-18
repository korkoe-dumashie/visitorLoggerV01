<x-layout>
    <x-slot:heading>
        Dashboard
    </x-slot:heading>

    @php
        $imageUrl = asset('PS-logo.png');
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let imageUrl = `{{ $imageUrl }}`;
            
            @if(session('success_type') == 'visitor_departure')
                let title = 'Good Bye!';
                let text = 'Thank you for visiting us today. We hope to see you again soon!';
            @elseif(session('success_type') == 'visitor_arrival')
                let title = 'Welcome to PaySwitch!';
                let text = 'We are happy to have you. Enjoy your visit';
            @elseif(session('success_type') == 'key_pickup')
                let title = 'Key Logged!';
                let text = 'The key pickup has been recorded successfully.';
            @elseif(session('success_type') == 'device_logged')
                let title = 'Device Logged!';
                let text = 'The device has been logged successfully.';
            @endif
            
            Swal.fire({
                title: title,
                text: text,
                imageUrl: imageUrl,
                imageWidth: 150,
                imageHeight: 100,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '{{ url("/") }}';
            });
        });
    </script>

    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? "Good Morning" : ($hour < 18 ? "Good Afternoon" : "Good Evening");
    @endphp 

    <div class="flex justify-between items-center p-4 lg:p-8">
        <h1 class="flex items-center gap-2 md:gap-3">
            <span class="text-xl sm:text-2xl lg:text-4xl font-medium">{{ $greeting }}</span>
            <span class="text-[#0F51AE] text-sm sm:text-base lg:text-xl rounded-full bg-[#F2F8FF] px-3 py-1 font-semibold">{{ Auth::user()->name }}</span> 
        </h1>
    </div>

    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id !== 5, 'visits', 'create'))
    <main class="flex flex-col">
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6 xl:gap-10 p-4 lg:p-8">
            <div class="flex rounded-2xl bg-[#F2F8FF] w-full p-4 sm:p-5 gap-4 sm:gap-6 flex-col justify-between">
                <h3 class="text-lg sm:text-xl lg:text-2xl text-black/50 flex gap-2 items-center font-semibold">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-current sm:w-5 sm:h-5">
                        <path d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H6C4.93913 15 3.92172 15.4214 3.17157 16.1716C2.42143 16.9217 2 17.9391 2 19V21M22 21V19C21.9993 18.1137 21.7044 17.2528 21.1614 16.5523C20.6184 15.8519 19.8581 15.3516 19 15.13M16 3.13C16.8604 3.3503 17.623 3.8507 18.1676 4.55231C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M13 7C13 9.20914 11.2091 11 9 11C6.79086 11 5 9.20914 5 7C5 4.79086 6.79086 3 9 3C11.2091 3 13 4.79086 13 7Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="hidden sm:inline">Ongoing Visits</span>
                    <span class="sm:hidden">Visits</span>
                </h3>
                <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold">{{ $visitor ? count($visitor) : 0 }}</h1>
                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
                    <a href="{{ url('check-visitor') }}" class="bg-gradient-to-b px-4 lg:px-8 text-sm md:text-base lg:text-lg rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] text-center">Log Visitor</a>
                    @endif
                    <a href="{{ url('visits') }}" class="flex items-center justify-center sm:justify-start text-green-700 font-bold text-sm sm:text-base lg:text-xl">
                        All visits
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-1 sm:w-4 sm:h-4">
                            <path d="M9 18L15 12L9 6" stroke="#15803D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="flex rounded-2xl bg-[#F2F8FF] w-full p-4 sm:p-5 gap-4 sm:gap-6 flex-col justify-between">
                <h3 class="text-lg sm:text-xl lg:text-2xl text-black/50 flex gap-2 items-center font-semibold">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-current sm:w-5 sm:h-5">
                        <path d="M2.586 17.414C2.2109 17.789 2.00011 18.2976 2 18.828V21C2 21.2653 2.10536 21.5196 2.29289 21.7071C2.48043 21.8947 2.73478 22 3 22H6C6.26522 22 6.51957 21.8947 6.70711 21.7071C6.89464 21.5196 7 21.2653 7 21V20C7 19.7348 7.10536 19.4805 7.29289 19.2929C7.48043 19.1054 7.73478 19 8 19H9C9.26522 19 9.51957 18.8947 9.70711 18.7071C9.89464 18.5196 10 18.2653 10 18V17C10 16.7348 10.1054 16.4805 10.2929 16.2929C10.4804 16.1054 10.7348 16 11 16H11.172C11.7024 15.9999 12.211 15.7891 12.586 15.414L13.4 14.6C14.7898 15.0842 16.3028 15.0823 17.6915 14.5948C19.0801 14.1072 20.2622 13.1629 21.0444 11.9162C21.8265 10.6695 22.1624 9.19421 21.9971 7.73178C21.8318 6.26934 21.1751 4.90629 20.1344 3.86561C19.0937 2.82493 17.7307 2.16822 16.2683 2.00293C14.8058 1.83763 13.3306 2.17353 12.0839 2.95568C10.8372 3.73782 9.89279 4.91991 9.40525 6.30856C8.91771 7.69721 8.91585 9.2102 9.4 10.6L2.586 17.414Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.5 8.00004C16.7761 8.00004 17 7.77618 17 7.50004C17 7.2239 16.7761 7.00004 16.5 7.00004C16.2239 7.00004 16 7.2239 16 7.50004C16 7.77618 16.2239 8.00004 16.5 8.00004Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="hidden sm:inline">Picked Keys</span>
                    <span class="sm:hidden">Keys</span>
                </h3>
                <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold">{{ $keys ? count($keys) : 0 }}</h1>
                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
                    <a href="{{ url('pick-key') }}" class="bg-gradient-to-b px-4 lg:px-8 text-sm md:text-base lg:text-lg rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] text-center">Log Key</a>
                    @endif
                    <a href="{{ url('keys') }}" class="flex items-center justify-center sm:justify-start text-green-700 font-bold text-sm sm:text-base lg:text-xl">
                        Keys
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-1 sm:w-4 sm:h-4">
                            <path d="M9 18L15 12L9 6" stroke="#15803D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>

            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
            <div class="flex rounded-2xl bg-[#F2F8FF] w-full p-4 sm:p-5 gap-4 sm:gap-6 flex-col justify-between">
                <h3 class="text-lg sm:text-xl lg:text-2xl text-black/50 flex gap-2 items-center font-semibold">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-current sm:w-5 sm:h-5">
                        <path d="M18 8V6C18 5.46957 17.7893 4.96086 17.4142 4.58579C17.0391 4.21071 16.5304 4 16 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V13C2 13.5304 2.21071 14.0391 2.58579 14.4142C2.96086 14.7893 3.46957 15 4 15H12M10 19V15.04V18.19M7 19H12M18 12H20C21.1046 12 22 12.8954 22 14V20C22 21.1046 21.1046 22 20 22H18C16.8954 22 16 21.1046 16 20V14C16 12.8954 16.8954 12 18 12Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="hidden sm:inline">Logged Devices</span>
                    <span class="sm:hidden">Devices</span>
                </h3>
                <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold">{{ $devices ? count($devices) : 0 }}</h1>
                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
                    <a href="{{ url('log') }}" class="bg-gradient-to-b px-4 lg:px-8 text-sm sm:text-base lg:text-xl rounded-lg py-2 text-white from-[#247EFC] to-[#0C66E4] text-center">Log Device</a>
                    @endif
                    <a href="{{ url('devices') }}" class="flex items-center justify-center sm:justify-start text-green-700 font-bold text-sm sm:text-base lg:text-xl">
                        Devices
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-1 sm:w-4 sm:h-4">
                            <path d="M9 18L15 12L9 6" stroke="#15803D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div id="visitors-table" class="overflow-x-auto p-4 md:p-8">
            @if ($visitor->isEmpty())
                <table class="w-full text-sm text-left text-gray-500 min-w-[600px]">
                    <thead class="text-xs text-gray-700 capitalize bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 sm:px-6 text-lg sm:text-xl lg:text-2xl py-3" colspan="5">
                                <h2 class="font-bold text-xl sm:text-2xl lg:text-3xl">Ongoing Visits</h2>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center py-16 sm:py-24 lg:py-32">
                                <h1 class="text-lg sm:text-xl lg:text-3xl text-gray-600">No visits ongoing</h1>
                                <p class="text-gray-500 text-sm sm:text-base lg:text-lg mt-2">Log a visitor by clicking the Log Visitor Button</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <table class="w-full text-sm text-left text-gray-500 min-w-[600px]">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 sm:px-6 text-lg sm:text-xl lg:text-2xl py-3" colspan="5">
                                <h2 class="font-bold text-xl sm:text-2xl lg:text-3xl">Ongoing Visits</h2>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="px-3 sm:px-6 text-base sm:text-lg lg:text-xl py-3">Name</th>
                            <th scope="col" class="px-3 sm:px-6 text-base sm:text-lg lg:text-xl py-3">Visiting</th>
                            <th scope="col" class="px-3 sm:px-6 text-base sm:text-lg lg:text-xl py-3">Purpose</th>
                            <th scope="col" class="px-3 sm:px-6 text-base sm:text-lg lg:text-xl py-3">Time In</th>
                            <th class="px-3 sm:px-6 py-6" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm sm:text-base">
                        @foreach ($visitor as $person)
                            <tr class="odd:bg-white even:bg-gray-50 border-b">
                                <th scope="row" class="px-3 sm:px-6 py-4 text-sm sm:text-base lg:text-xl font-medium text-black whitespace-nowrap">{{ $person->full_name }}</th>
                                <td class="px-3 sm:px-6 text-black text-sm sm:text-base lg:text-xl py-4">{{ $person->visitee ? $person->visitee->first_name . ' ' . $person->visitee->last_name : 'N/A' }}</td>
                                <td class="px-3 sm:px-6 py-4">
                                    @switch($person['purpose'])
                                        @case('personal')
                                            <span class="text-green-700 bg-green-200 text-xs sm:text-sm lg:text-base py-1 px-2 sm:px-3 rounded-2xl">{{ $person['purpose'] }}</span>
                                            @break
                                        @case('interview')
                                            <span class="text-amber-600 bg-amber-100 text-xs sm:text-sm lg:text-base py-1 px-2 sm:px-3 rounded-2xl">{{ $person['purpose'] }}</span>
                                            @break
                                        @case('official')
                                            <span class="text-red-600 bg-red-100 text-xs sm:text-sm lg:text-base py-1 px-2 sm:px-3 rounded-2xl">{{ $person['purpose'] }}</span>
                                            @break
                                        @default
                                            <span class="text-blue-600 bg-blue-100 text-xs sm:text-sm lg:text-base rounded-2xl py-1 px-2 sm:px-3">{{ $person['purpose'] }}</span>
                                    @endswitch
                                </td>
                                <td class="px-3 sm:px-6 text-sm sm:text-base lg:text-xl py-4">{{ $person?->created_at?->format('H:i') }}</td>
                                <td class="px-3 sm:px-6 py-4">
                                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2">
                                        <a href="{{ url('visit/' . $person->id) }}" class="font-medium text-blue-600 text-sm sm:text-base lg:text-xl hover:underline">View</a>
                                        @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'visits', 'create'))
                                            <a href="{{ route('visitor.departure', ['visitor' => $person->id]) }}" class="font-medium text-red-500 px-2 sm:px-3 py-1 text-xs sm:text-sm rounded-lg border border-red-400 whitespace-nowrap">Sign Out</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-3 sm:px-6 py-4">
                    {{ $visitor->links() }}
                </div>
            @endif
        </div>
    </main>

    @endif

    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id == 5, 'visits', 'create'))
    <main class="min-h-[calc(100vh-18rem)] lg:min-h-[calc(100vh-28rem)] bg-gray-50 flex items-center justify-center px-4 lg:px-8 py-6 lg:py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 md:gap-16 max-w-5xl w-full">
            <a href="{{ url('check-visitor') }}" class="group flex flex-col items-center bg-white rounded-2xl p-6 sm:p-8 transition-all duration-300 hover:shadow-lg border border-gray-200 hover:border-blue-100 hover:scale-[1.02]">
                <div class="p-3 sm:p-4 bg-blue-50 rounded-full mb-4 sm:mb-6 group-hover:bg-blue-100 transition-colors">
                    <img src="{{ asset('entry-new.svg') }}" alt="Sign In Icon" class="w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-40 lg:h-40 transition-transform group-hover:scale-110">
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900 mb-2">Sign In</h2>
                <p class="text-gray-600 text-base sm:text-lg lg:text-xl text-center">Register your arrival</p>
                <div class="mt-4 sm:mt-6 inline-flex text-base sm:text-lg lg:text-xl items-center text-blue-600 font-medium">
                    Get Started 
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>
            <a href="{{ url('check-exit') }}" class="group flex flex-col items-center bg-white rounded-2xl p-6 sm:p-8 transition-all duration-300 hover:shadow-lg border border-gray-200 hover:border-red-100 hover:scale-[1.02]">
                <div class="p-3 sm:p-4 bg-red-50 rounded-full mb-4 sm:mb-6 group-hover:bg-red-100 transition-colors">
                    <img src="{{ asset('exit-new.svg') }}" alt="Sign Out Icon" class="w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-40 lg:h-40 transition-transform group-hover:scale-110">
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900 mb-2">Sign Out</h2>
                <p class="text-gray-600 text-base sm:text-lg lg:text-xl text-center">Register your departure</p>
                <div class="mt-4 sm:mt-6 inline-flex text-base sm:text-lg lg:text-xl items-center text-red-600 font-medium">
                    Exit Now
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>
        </div>
    </main>
    <aside class="fixed bottom-4 sm:bottom-8 left-1/2 -translate-x-1/2 z-50">
        <button id="visitorDropDown" class="p-2 sm:p-3 bg-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group border border-gray-100" aria-label="Menu">
            <svg width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" class="text-gray-600 group-hover:text-blue-600 transition-colors sm:w-7 sm:h-7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19.4 15C19.2669 15.3016 19.2272 15.6362 19.286 15.9606C19.3448 16.285 19.4995 16.5843 19.73 16.82L19.79 16.88C19.976 17.0657 20.1235 17.2863 20.2241 17.5291C20.3248 17.7719 20.3766 18.0322 20.3766 18.295C20.3766 18.5578 20.3248 18.8181 20.2241 19.0609C20.1235 19.3037 19.976 19.5243 19.79 19.71C19.6043 19.896 19.3837 20.0435 19.1409 20.1441C18.8981 20.2448 18.6378 20.2966 18.375 20.2966C18.1122 20.2966 17.8519 20.2448 17.6091 20.1441C17.3663 20.0435 17.1457 19.896 16.96 19.71L16.9 19.65C16.6643 19.4195 16.365 19.2648 16.0406 19.206C15.7162 19.1472 15.3816 19.1869 15.08 19.32C14.7842 19.4468 14.532 19.6572 14.3543 19.9255C14.1766 20.1938 14.0813 20.5082 14.08 20.83V21C14.08 21.5304 13.8693 22.0391 13.4942 22.4142C13.1191 22.7893 12.6104 23 12.08 23C11.5496 23 11.0409 22.7893 10.6658 22.4142C10.2907 22.0391 10.08 21.5304 10.08 21V20.91C10.0723 20.579 9.96512 20.258 9.77251 19.9887C9.5799 19.7194 9.31074 19.5143 9 19.4C8.69838 19.2669 8.36381 19.2272 8.03941 19.286C7.71502 19.3448 7.41568 19.4995 7.18 19.73L7.12 19.79C6.93425 19.976 6.71368 20.1235 6.47088 20.2241C6.22808 20.3248 5.96783 20.3766 5.705 20.3766C5.44217 20.3766 5.18192 20.3248 4.93912 20.2241C4.69632 20.1235 4.47575 19.976 4.29 19.79C4.10405 19.6043 3.95653 19.3837 3.85588 19.1409C3.75523 18.8981 3.70343 18.6378 3.70343 18.375C3.70343 18.1122 3.75523 17.8519 3.85588 17.6091C3.95653 17.3663 4.10405 17.1457 4.29 16.96L4.35 16.9C4.58054 16.6643 4.73519 16.365 4.794 16.0406C4.85282 15.7162 4.81312 15.3816 4.68 15.08C4.55324 14.7842 4.34276 14.532 4.07447 14.3543C3.80618 14.1766 3.49179 14.0813 3.17 14.08H3C2.46957 14.08 1.96086 13.8693 1.58579 13.4942C1.21071 13.1191 1 12.6104 1 12.08C1 11.5496 1.21071 11.0409 1.58579 10.6658C1.96086 10.2907 2.46957 10.08 3 10.08H3.09C3.42099 10.0723 3.742 9.96512 4.0113 9.77251C4.28059 9.5799 4.48572 9.31074 4.6 9C4.73312 8.69838 4.77282 8.36381 4.714 8.03941C4.65519 7.71502 4.50054 7.41568 4.27 7.18L4.21 7.12C4.02405 6.93425 3.87653 6.71368 3.77588 6.47088C3.67523 6.22808 3.62343 5.96783 3.62343 5.705C3.62343 5.44217 3.67523 5.18192 3.77588 4.93912C3.87653 4.69632 4.02405 4.47575 4.21 4.29C4.39575 4.10405 4.61632 3.95653 4.85912 3.85588C5.10192 3.75523 5.36217 3.70343 5.625 3.70343C5.88783 3.70343 6.14808 3.75523 6.39088 3.85588C6.63368 3.95653 6.85425 4.10405 7.04 4.29L7.1 4.35C7.33568 4.58054 7.63502 4.73519 7.95941 4.794C8.28381 4.85282 8.61838 4.81312 8.92 4.68H9C9.29577 4.55324 9.54802 4.34276 9.72569 4.07447C9.90337 3.80618 9.99872 3.49179 10 3.17V3C10 2.46957 10.2107 1.96086 10.5858 1.58579C10.9609 1.21071 11.4696 1 12 1C12.5304 1 13.0391 1.21071 13.4142 1.58579C13.7893 1.96086 14 2.46957 14 3V3.09C14.0013 3.41179 14.0966 3.72618 14.2743 3.99447C14.452 4.26276 14.7042 4.47324 15 4.6C15.3016 4.73312 15.6362 4.77282 15.9606 4.714C16.285 4.65519 16.5843 4.50054 16.82 4.27L16.88 4.21C17.0657 4.02405 17.2863 3.87653 17.5291 3.77588C17.7719 3.67523 18.0322 3.62343 18.295 3.62343C18.5578 3.62343 18.8181 3.67523 19.0609 3.77588C19.3037 3.87653 19.5243 4.02405 19.71 4.21C19.896 4.39575 20.0435 4.61632 20.1441 4.85912C20.2448 5.10192 20.2966 5.36217 20.2966 5.625C20.2966 5.88783 20.2448 6.14808 20.1441 6.39088C20.0435 6.63368 19.896 6.85425 19.71 7.04L19.65 7.1C19.4195 7.33568 19.2648 7.63502 19.206 7.95941C19.1472 8.28381 19.1869 8.61838 19.32 8.92V9C19.4468 9.29577 19.6572 9.54802 19.9255 9.72569C20.1938 9.90337 20.5082 9.99872 20.83 10H21C21.5304 10 22.0391 10.2107 22.4142 10.5858C22.7893 10.9609 23 11.4696 23 12C23 12.5304 22.7893 13.0391 22.4142 13.4142C22.0391 13.7893 21.5304 14 21 14H20.91C20.5882 14.0013 20.2738 14.0966 20.0055 14.2743C19.7372 14.452 19.5268 14.7042 19.4 15Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div id="visitorDropUp" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-4 hidden">
            <form action="{{ url('logout') }}" method="POST" class="flex flex-col items-center">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-6 py-3 bg-white text-red-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:bg-red-50 border border-gray-100 group">
                    <svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none" class="transition-transform group-hover:rotate-12">
                        <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9M16 17L21 12M21 12L16 7M21 12H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>
    @endif

    <script src="{{ asset('/js/index.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const visitorDropDown = document.getElementById('visitorDropDown');
            const visitorDropUp = document.getElementById('visitorDropUp');
            
            visitorDropDown.addEventListener('click', function() {
                visitorDropUp.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function(event) {
                if (!visitorDropDown.contains(event.target) && !visitorDropUp.contains(event.target)) {
                    visitorDropUp.classList.add('hidden');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const signOutLinks = document.querySelectorAll('a[href^="departure?visitor="]');
            
            signOutLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const visitorName = row.querySelector('th').innerText;
                    
                    Swal.fire({
                        title: 'Sign Out Confirmation',
                        text: `Are you sure you want to sign out ${visitorName}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, sign out',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = this.getAttribute('href');
                        }
                    });
                });
            });
        });

        function showToast(icon, title, text) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 8000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: icon,
                title: title,
                text: text
            });
        }
    </script>
</x-layout>