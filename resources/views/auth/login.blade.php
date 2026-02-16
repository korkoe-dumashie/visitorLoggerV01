@php
session_start();
if (isset($_SESSION['logged_in'])) {
    header("Location: /");
    exit();
}
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @vite('resources/css/app.css')
    <title>PaySwitch Logger</title>
</head>

<body class="flex w-full justify-center items-center h-screen">

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg absolute top-10">
    <ul>
        @foreach ($errors->all() as $error)
            <li class="font-bold">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<main class="flex flex-col items-center gap-12">
    <img src="{{ asset('logo.png') }}" class="lg:w-56 w-40">

    <section class="p-10 bg-[#f5f5f5] lg:w-[520px] rounded-3xl space-y-10">
        <h1 class="text-center font-bold text-2xl text-[#201f21]">
            Login to your Account
        </h1>

        <form action="{{ url('login') }}" method="POST" class="space-y-8">
            @csrf

            <div>
                <label class="text-[#44546f] font-semibold">Email / Username</label>
                <input type="text" name="email" required
                       value="{{ old('email') }}"
                       class="w-full rounded p-2 border bg-white"
                       placeholder="aaron@payswitch.com.gh">
            </div>

            <div>
                <label class="text-[#44546f] font-semibold">Password</label>
                <div class="flex items-center bg-white border rounded px-2">
                    <input type="password" name="password" id="password"
                           required
                           class="w-full outline-none p-2"
                           placeholder="...........">

                    <!-- SINGLE ICON -->
                    {{-- <svg id="togglePassword" width="24" height="24"
                         class="cursor-pointer"
                         viewBox="0 0 24 24"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"
                              stroke="#555" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3"
                                stroke="#555" stroke-width="2"/>
                    </svg> --}}
                </div>
            </div>

            <button type="submit"
                    class="w-full rounded p-2 bg-gradient-to-b from-[#247efc] to-[#0c66e4] text-white">
                Login
            </button>
        </form>
    </section>
</main>

<script>
$(function () {
    let passwordField = $("#password");
    let toggleIcon = $("#togglePassword");
    let visible = false;

    toggleIcon.on("click", function () {
        visible = !visible;
        passwordField.attr("type", visible ? "text" : "password");
    });

    // prevent back button after login
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
});
</script>

</body>
</html>
