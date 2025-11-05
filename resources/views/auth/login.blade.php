@php

session_start();
if (isset($_SESSION['logged_in'])) {
  // User is already logged in, redirect to homepage
  header("/");
  exit();
}
@endphp






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
    <title>PaySwitch Logger</title>
</head>
<body class="flex w-full justify-center m-auto items-center h-screen">
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg absolute top-0 bottom-1/3 left-0 right-0 w-fit h-fit m-auto" role="alert">
        {{-- <strong class="font-bold">Error!</strong> --}}
        <ul class="">
            @foreach ($errors->all() as $error)
                <li class="text-base font-bold">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <main class="flex flex-col items-center justify-center mx-auto gap-12">
        <div class="flex m-auto">
            <img src={{asset('logo.png')}} alt="" class="lg:w-56 w-40 md:w-60">
        </div>
        <section class="p-10 bg-[#f5f5f5] lg:w-[520px] flex flex-col rounded-3xl gap-12">
            <h1 class="text-center font-bold  text-2xl text-[#201f21]">
                Login to your Account
            </h1>
            <form action="{{ url('login') }}" method="POST" class="flex flex-col gap-8">
                @csrf
                <div class="lg:w-96">
                    <label for="email" class="capitalize text-[#44546f] font-semibold text-lg">Email / Username</label>
                    <input type="text" required name="email" value="{{ old('email') }}" id="email" class="w-full rounded p-2 border outline-none border-[#091e4223] lg:w-[420px]  bg-white" placeholder="aaron@payswitch.com.gh">
                </div>
                <div class="lg:w-96">
                    <label for="password" class="capitalize text-base text-[#44546f] font-semibold">password</label>
                <div class="bg-white border  lg:w-[420px] flex items-center rounded border-[#091e4223]">
                    <div class="w-full lg:px-1 lg:p-0 p-2 flex rounded items-center">
                    <input type="password" required name="password" id="password" class="w-full text-lg outline-none lg:p-3 bg-white" placeholder="...........">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.06199 12.848C1.97865 12.6235 1.97865 12.3765 2.06199 12.152C2.87369 10.1838 4.2515 8.50103 6.02076 7.31689C7.79001 6.13275 9.87103 5.50061 12 5.50061C14.1289 5.50061 16.21 6.13275 17.9792 7.31689C19.7485 8.50103 21.1263 10.1838 21.938 12.152C22.0213 12.3765 22.0213 12.6235 21.938 12.848C21.1263 14.8161 19.7485 16.499 17.9792 17.6831C16.21 18.8672 14.1289 19.4994 12 19.4994C9.87103 19.4994 7.79001 18.8672 6.02076 17.6831C4.2515 16.499 2.87369 14.8161 2.06199 12.848Z" stroke="#292d3299" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 15.5C13.6568 15.5 15 14.1568 15 12.5C15 10.8431 13.6568 9.49999 12 9.49999C10.3431 9.49999 8.99999 10.8431 8.99999 12.5C8.99999 14.1568 10.3431 15.5 12 15.5Z" stroke="#292d3299" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <svg width="24" height="25" viewBox="0 0 24 25" class="cursor-pointer" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.733 5.576C13.0624 5.2984 15.4186 5.79082 17.4419 6.97805C19.4651 8.16528 21.0442 9.98208 21.938 12.151C22.0213 12.3755 22.0213 12.6225 21.938 12.847C21.5705 13.738 21.0848 14.5755 20.494 15.337M14.084 14.658C13.5182 15.2045 12.7604 15.5069 11.9738 15.5C11.1872 15.4932 10.4348 15.1777 9.87853 14.6215C9.32231 14.0652 9.0068 13.3128 8.99996 12.5262C8.99313 11.7396 9.29551 10.9818 9.84199 10.416M17.479 17.999C16.1525 18.7848 14.6724 19.276 13.1393 19.4394C11.6062 19.6028 10.0559 19.4345 8.59362 18.9459C7.1313 18.4573 5.79118 17.6599 4.6642 16.6077C3.53722 15.5556 2.64974 14.2734 2.06199 12.848C1.97865 12.6235 1.97865 12.3765 2.06199 12.152C2.94862 10.0019 4.50866 8.19725 6.50799 7.009M1.99999 2.5L22 22.5" stroke="#292d3299" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                    </div>
                </div>
                </div>

                {{-- <label for="saveLogins" class="flex gap-4 items-center">
                    <input type="checkbox" name="" id="">
                    Save my logins
                </label> --}}

                <button type="submit" class="md:w-[420px] w-full rounded p-2 bg-gradient-to-b from-[#247efc] to-[#0c66e4] text-white ">Login</button>
            </form>
        </section>
    </main>












    <script>
 $(document).ready(function () {
            let passwordField = $("#password");
            let showPasswordIcon = $("svg:first-of-type");
            let hidePasswordIcon = $("svg:last-of-type");

            hidePasswordIcon.hide(); // Initially hide the "hide password" icon

            showPasswordIcon.on("click", function () {
                passwordField.attr("type", "text");
                showPasswordIcon.hide();
                hidePasswordIcon.show();
            });

            hidePasswordIcon.on("click", function () {
                passwordField.attr("type", "password");
                hidePasswordIcon.hide();
                showPasswordIcon.show();
            });

            history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

            // Sweet Alert Toast Function
            function showToast(icon, title, text) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
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
                showToast('success', 'Success!', "{{ session('success') }}");
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
