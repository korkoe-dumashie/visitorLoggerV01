{{-- @php
    session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE) {
  header('login');
  exit;
} else{
  header('/');
}
@endphp --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="">
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    @vite('resources/css/app.css')
    <title>Visitor Log</title>
</head>

<body class="flex w-full">

    @if(\App\Models\Roles::hasPermission(auth()->user()->role_id !== 5, 'visits', 'create'))


    <nav class="flex flex-col bg-[#0F51AE] min-h-screen lg:w-1/6">
        <div class="lg:p-10 py-4 justify-center lg:justify-start flex w-full border-b border-[#529AFF]">
            <img src="{{ asset('payswitch.png') }}" class="lg:w-2/3 hidden lg:flex" alt="">
            <img src="{{ asset('small-logo.png') }}" class="lg:hidden w-20" alt="">
        </div>

        <div class="flex lg:p-10 p-4 h-full justify-between   md:py-4  flex-col">
            <ul class="flex flex-col gap-10 lg:gap-0 lg:p-0 justify-between">
                <li class="">
                    <x-nav-link href="{{ url('/') }}" :active="request()->is('/')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-9 lg:size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>

                        <span class="hidden lg:flex">Dashboard</span>
                    </x-nav-link>
                </li>
                @if(\App\Models\Roles::hasPermission(auth()->user()->role_id !== 5, 'visits', 'create'))
                <li>
                <x-nav-link href="{{ url('visits') }}" :active="request()->is('visits')">
                    <svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-9 lg:size-6">
                        <path d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H6C4.93913 15 3.92172 15.4214 3.17157 16.1716C2.42143 16.9217 2 17.9391 2 19V21M22 21V19C21.9993 18.1137 21.7044 17.2528 21.1614 16.5523C20.6184 15.8519 19.8581 15.3516 19 15.13M16 3.13C16.8604 3.3503 17.623 3.8507 18.1676 4.55231C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M13 7C13 9.20914 11.2091 11 9 11C6.79086 11 5 9.20914 5 7C5 4.79086 6.79086 3 9 3C11.2091 3 13 4.79086 13 7Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    <span class="hidden lg:flex">Visits</span>
                </x-nav-link>

                </li>

                <li class="">
                    <x-nav-link href="{{ url('keys') }}" :active="request()->is('keys')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9 lg:size-6">
                            <path d="M2.586 17.414C2.2109 17.789 2.00011 18.2976 2 18.828V21C2 21.2653 2.10536 21.5196 2.29289 21.7071C2.48043 21.8947 2.73478 22 3 22H6C6.26522 22 6.51957 21.8947 6.70711 21.7071C6.89464 21.5196 7 21.2653 7 21V20C7 19.7348 7.10536 19.4805 7.29289 19.2929C7.48043 19.1054 7.73478 19 8 19H9C9.26522 19 9.51957 18.8947 9.70711 18.7071C9.89464 18.5196 10 18.2653 10 18V17C10 16.7348 10.1054 16.4805 10.2929 16.2929C10.4804 16.1054 10.7348 16 11 16H11.172C11.7024 15.9999 12.211 15.7891 12.586 15.414L13.4 14.6C14.7898 15.0842 16.3028 15.0823 17.6915 14.5948C19.0801 14.1072 20.2622 13.1629 21.0444 11.9162C21.8265 10.6695 22.1624 9.19421 21.9971 7.73178C21.8318 6.26934 21.1751 4.90629 20.1344 3.86561C19.0937 2.82493 17.7307 2.16822 16.2683 2.00293C14.8058 1.83763 13.3306 2.17353 12.0839 2.95568C10.8372 3.73782 9.89279 4.91991 9.40525 6.30856C8.91771 7.69721 8.91585 9.2102 9.4 10.6L2.586 17.414Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5 8.00004C16.7761 8.00004 17 7.77618 17 7.50004C17 7.2239 16.7761 7.00004 16.5 7.00004C16.2239 7.00004 16 7.2239 16 7.50004C16 7.77618 16.2239 8.00004 16.5 8.00004Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>                          
                            <span class="hidden lg:flex">Keys</span>
                            
                    </x-nav-link>
                </li>

                <li class="">
                    <x-nav-link href="{{ url('devices') }}" :active="request()->is('devices')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9 lg:size-6">
                            <path d="M18 8V6C18 5.46957 17.7893 4.96086 17.4142 4.58579C17.0391 4.21071 16.5304 4 16 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V13C2 13.5304 2.21071 14.0391 2.58579 14.4142C2.96086 14.7893 3.46957 15 4 15H12M10 19V15.04V18.19M7 19H12M18 12H20C21.1046 12 22 12.8954 22 14V20C22 21.1046 21.1046 22 20 22H18C16.8954 22 16 21.1046 16 20V14C16 12.8954 16.8954 12 18 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="hidden lg:flex">Devices</span>
                            
                    </x-nav-link>
                </li>


                @endif
                @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'staff', 'view'))
                <li class="text-[#529AFF] hidden lg:block font-semibold text-lg">Records</li>
                <li class="">
                    <x-nav-link href="{{ url('staff') }}" :active="request()->is('staff')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9 lg:size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                          </svg>
                          
                        <span class="hidden lg:flex">Staff</span>
                    </x-nav-link>
                </li>
                @endif
                {{-- @if(true) --}}
                {{-- @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'reports', 'view'))
                <li class="">
                    <x-nav-link href="{{ url('records') }}" :active="request()->is('records')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-9 lg:size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>

                        <span class="hidden lg:flex">Records</span>
                    </x-nav-link>
                </li>

                @endif --}}

                @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'logs', 'view'))
                <li class="">
                    <x-nav-link href="{{ url('logs') }}" :active="request()->is('logs')">
                        <svg width="20" stroke="currentColor" class="size-9 lg:size-6" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 12H21M13 18H21M13 6H21M3 12H4M3 18H4M3 6H4M8 12H9M8 18H9M8 6H9" stroke="currentColor" stroke-width="2"  stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            


                        <span class="hidden lg:flex">Logs</span>
                    </x-nav-link>
                </li>
                @endif

                @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'user', 'view'))
                <li class="">
                    <x-nav-link href="{{ url('users') }}" :active="request()->is('users')">
                        <svg width="24" height="24"  class="size-9 lg:size-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 21C18 18.8783 17.1571 16.8434 15.6569 15.3431C14.1566 13.8429 12.1217 13 10 13M10 13C7.87827 13 5.84344 13.8429 4.34315 15.3431C2.84285 16.8434 2 18.8783 2 21M10 13C12.7614 13 15 10.7614 15 8C15 5.23858 12.7614 3 10 3C7.23858 3 5 5.23858 5 8C5 10.7614 7.23858 13 10 13ZM22 20C22 16.63 20 13.5 18 12C18.6574 11.5068 19.1831 10.8591 19.5306 10.1143C19.878 9.36945 20.0365 8.55047 19.992 7.7298C19.9475 6.90913 19.7014 6.11209 19.2755 5.4092C18.8495 4.70631 18.2569 4.11926 17.55 3.7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        <span class="hidden lg:flex">Users</span>
                    </x-nav-link>
                </li>
                @endif

                @if(\App\Models\Roles::hasPermission(auth()->user()->role_id, 'settings', 'view'))
                <li class="">
                    <x-nav-link href="{{ url('settings') }}" :active="request()->is('settings')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-9 lg:size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="hidden lg:flex">Settings</span>
                    </x-nav-link>
                </li>
                @endif


            </ul>

            <aside class="flex h-fit relative">
                <div id="userDropupButton" class="flex items-center gap-2 rounded-lg cursor-pointer">
                    <svg width="18" height="18" viewBox="0 0 135 122" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M106.838 121.876C123.413 109.586 134.167 89.8065 134.167 67.5C134.167 30.2208 104.132 0 67.0833 0C30.0342 0 0 30.2208 0 67.5C0 89.7364 10.6859 109.462 27.1726 121.76C29.0585 101.422 46.1699 85.5 67 85.5C87.8697 85.5 105.007 101.483 106.838 121.876ZM90 53.5C90 66.2025 79.7025 76.5 67 76.5C54.2975 76.5 44 66.2025 44 53.5C44 40.7975 54.2975 30.5 67 30.5C79.7025 30.5 90 40.7975 90 53.5Z" fill="#C8DFFF"/>
                    </svg>
                    
                    @php
                    $username = explode(" ", Auth::user()->name);
                    @endphp
                    
                    <span class="text-2xl font-semibold hidden lg:flex text-[#C8DFFF]">{{ $username[0] }}</span>
                    
                    <!-- Change arrow direction to point up -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 6L15 12L9 18" stroke="#C8DFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                
                <!-- Dropup menu -->
                <div id="userDropup" class="absolute bottom-full mb-2 hidden rounded-lg shadow-lg w-fit">
                    <form action="{{ url('logout') }}" method="POST">
                        @csrf
                        <button class="p-2 lg:p-3 bg-blue-500 flex items-center gap-2 text-white rounded-xl">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9M16 17L21 12M21 12L16 7M21 12H9" stroke="#C8DFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </nav>

    @endif


    

    <main class="w-full  lg:h-[calc(100vh-5rem)] h-[calc(100vh-6.5rem)] overflow-auto scrollbar-hidden  flex flex-col">
        <!-- Top Section -->
        <header class="flex justify-between items-center w-full border-b border-[#C8DFFF] px-10 py-5">
            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id !== 5, 'visits', 'create'))
                <h1 class=" lg:text-3xl font-bold text-xl text-gray-800 ">{{ $heading }}</h1>
            @endif

            @if(\App\Models\Roles::hasPermission(auth()->user()->role_id == 5, 'visits', 'create'))
            <a href="/" class="w-1/6 lg:w-1/12">
            <img src="{{ asset('PS-logo.png') }}" class="" alt=""></a>
            @endif
                <div class="lg:text-2xl w-full text-xl gap-10 flex justify-end items-center lg:w-fit text-[#0F51AE] rounded-3xl font-medium">
                    <span class="lg:text-2xl lg:w-fit text-[#0F51AE] rounded-3xl font-medium" id="date"></span>
                    <span class="lg:text-3xl lg:w-fit  text-[#0F51AE] rounded-3xl font-semibold" id="clock"></span>
                </div>
            </header>
    
        <!-- Text Section: Takes Remaining Space -->
        <div class="flex-col  flex">
            <div class="bg-white flex-col flex">
              {{ $slot }}
            </div>
        </div>
       
    </main>
    

    
   


    <script>
function updateClock() {
    const clockElement = document.getElementById('clock');
    const dateElement = document.getElementById('date'); // Element for the date
    const now = new Date();
    
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    // Format the date
    const options = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-US', options); 

    // Update clock
    clockElement.textContent = `${hours}:${minutes}:${seconds}`;

    // Update date
    dateElement.textContent = formattedDate;
}

// Update clock immediately
updateClock();

// Update clock every second
setInterval(updateClock, 1000);


document.addEventListener('DOMContentLoaded', function() {
    const userDropupButton = document.getElementById('userDropupButton');
    const userDropup = document.getElementById('userDropup');
    
    // Toggle dropup menu when clicking the button
    userDropupButton.addEventListener('click', function() {
        userDropup.classList.toggle('hidden');
    });
    
    // Close dropup when clicking outside
    document.addEventListener('click', function(event) {
        if (!userDropupButton.contains(event.target) && !userDropup.contains(event.target)) {
            userDropup.classList.add('hidden');
        }
    });
});



        document.addEventListener('DOMContentLoaded', function() {
    // Your existing showToast function
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

    // Check for session flash messages
    @if(session('success'))
        // Check if this is a login success message
        @if(strpos(session('success'), 'Welcome back') !== false)
            showToast('success', 'Logged In!', "{{ session('success') }}");
        @else
            showToast('success', 'Success!', "{{ session('success') }}");
        @endif
    @endif

    @if(session('error'))
        showToast('error', 'Error!', "{{ session('error') }}");
    @endif

    @if($errors->any())
        showToast('error', 'Error!', "{{ $errors->first() }}");
    @endif
});
    </script>



</body>
</html>